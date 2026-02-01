<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\Lingkungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    // Daftar Bidang Tetap (Sesuai Request)
    private $fixedBidang = [
        'Pengurus Harian',
        'Tim Pelayanan Bidang Liturgi',
        'Tim Pelayanan Bidang Sarana dan Prasarana',
        'Tim Pelayanan Bidang Umum',
        'Tim Pelayanan Bidang Pewartaan dan Pelayanan'
    ];

    public function index(Request $request)
    {
        $bidangList = $this->fixedBidang;
        $currentBidang = $request->query('bidang'); 

        $query = \App\Models\OrganizationMember::query();

        if ($currentBidang) {
            $query->where('bidang', $currentBidang);
        }

        // ORDERING LOGIC:
        // 1. Group Order (sub_bidang_order)
        // 2. Group Name (fallback if order is same)
        // 3. Member Order (sort_order)
        $members = $query->orderBy('bidang')
                         ->orderBy('sub_bidang_order', 'asc') 
                         ->orderBy('sub_bidang', 'asc') 
                         ->orderBy('sort_order', 'asc')
                         ->get();
        
        return view('admin.organization.index', compact('members', 'bidangList', 'currentBidang'));
    }

    public function create()
    {
        $lingkungans = \App\Models\Lingkungan::all();
        
        // AMBIL DATA HISTORY SUB BIDANG (Untuk Autocomplete)
        // Kita ambil semua kombinasi bidang & sub_bidang yang unik
        $existingSubBidang = \App\Models\OrganizationMember::select('bidang', 'sub_bidang')
                                ->whereNotNull('sub_bidang')
                                ->distinct()
                                ->get()
                                ->groupBy('bidang'); // Dikelompokkan per bidang

        return view('admin.organization.create', [
            'lingkungans' => $lingkungans,
            'bidangList' => $this->fixedBidang,
            'existingSubBidang' => $existingSubBidang // <-- Kirim variabel ini
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // ... validasi lainnya ...
        ]);

        $data = $request->all();

        // --- TAMBAHAN: Handle Checkbox ---
        // Jika checkbox 'tampil_di_menu' dicentang, nilainya true, jika tidak false.
        $data['tampil_di_menu'] = $request->has('tampil_di_menu');
        // ---------------------------------

        $data['sub_bidang'] = $this->normalizeSubBidang($request->sub_bidang);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/organization', 'public');
        }

        OrganizationMember::create($data);
        
        // --- TAMBAHAN: Update semua anggota di tim yang sama ---
        // Jika dicentang, semua anggota lain di tim ini juga akan ikut tampil
        if ($data['tampil_di_menu']) {
            OrganizationMember::where('bidang', $data['bidang'])
                              ->where('sub_bidang', $data['sub_bidang'])
                              ->update(['tampil_di_menu' => true]);
        }

        return redirect()->route('admin.organization.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $member = \App\Models\OrganizationMember::findOrFail($id);
        $lingkungans = \App\Models\Lingkungan::all();
        
        // Sama seperti create, ambil history
        $existingSubBidang = \App\Models\OrganizationMember::select('bidang', 'sub_bidang')
                                ->whereNotNull('sub_bidang')
                                ->distinct()
                                ->get()
                                ->groupBy('bidang');

        return view('admin.organization.edit', [
            'member' => $member,
            'lingkungans' => $lingkungans,
            'bidangList' => $this->fixedBidang,
            'existingSubBidang' => $existingSubBidang
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = OrganizationMember::findOrFail($id);

        $request->validate([
            'name' => 'required',
            // ... validasi lainnya ...
        ]);

        $data = $request->all();

        // --- TAMBAHAN: Handle Checkbox ---
        $data['tampil_di_menu'] = $request->has('tampil_di_menu');
        // ---------------------------------

        $data['sub_bidang'] = $this->normalizeSubBidang($request->sub_bidang);
        
        if ($request->hasFile('image')) {
            // ... kode upload ...
        }

        $member->update($data);
        
        // --- TAMBAHAN: Sinkronisasi status tampil untuk seluruh tim ---
        // Apapun pilihan (centang/tidak), samakan untuk semua anggota di tim tersebut.
        OrganizationMember::where('bidang', $data['bidang'])
                          ->where('sub_bidang', $data['sub_bidang'])
                          ->update(['tampil_di_menu' => $data['tampil_di_menu']]);

        return redirect()->route('admin.organization.index')->with('success', 'Data diperbarui');
    }

    public function destroy($id)
    {
        $member = OrganizationMember::findOrFail($id);
        if ($member->image) Storage::disk('public')->delete($member->image);
        $member->delete();
        return back()->with('success', 'Anggota dihapus');
    }
    
    public function reorder(Request $request)
    {
        foreach ($request->ids as $index => $id) {
            OrganizationMember::where('id', $id)->update(['sort_order' => $index + 1]);
        }
        return response()->json(['status' => 'success']);
    }

    public function reorderTeams(Request $request)
    {
        // Expects: 
        // teams: ['Koordinator', 'Koor', 'Koster'] (Ordered array of names)
        // bidang: 'Tim Pelayanan Bidang Liturgi'
        
        $bidang = $request->bidang;
        $teams = $request->teams;

        foreach ($teams as $index => $subName) {
            // Update ALL members belonging to this sub-team with the new index
            \App\Models\OrganizationMember::where('bidang', $bidang)
                ->where('sub_bidang', $subName)
                ->update(['sub_bidang_order' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }

    private function normalizeSubBidang($text)
    {
        if (empty($text)) return null;

        // 1. Ubah ke format Title Case (Contoh: "tata laksana" -> "Tata Laksana")
        // Ini berlaku untuk SEMUA input.
        $normalized = ucwords(strtolower($text));

        // 2. (Opsional) Daftar Singkatan yang wajib Huruf Besar Semua
        // Anda bisa menambah daftar ini sesuka hati (misal: PSE, KPHB, dll)
        $acronyms = ['Omk', 'Pia', 'Pir', 'Komsos', 'Pse', 'Kphb', 'App'];

        // Cek apakah hasil normalisasi ada di daftar singkatan
        // Jika "Omk", paksa jadi "OMK". Jika "Tata Laksana", biarkan tetap.
        if (in_array($normalized, $acronyms)) {
            return strtoupper($normalized);
        }
        
        // Handle kasus gabungan seperti "PIA & PIR" atau "Pendamping OMK"
        // Kita replace kata per kata
        foreach ($acronyms as $acro) {
            // Ganti "Omk" menjadi "OMK" jika ada di dalam kalimat
            $normalized = str_replace($acro, strtoupper($acro), $normalized);
        }

        return $normalized;
    }
}