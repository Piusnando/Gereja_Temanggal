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
        $bidang = $request->query('bidang'); 

        $query = OrganizationMember::query();

        if ($bidang) {
            $query->where('bidang', $bidang);
        }

        // Urutkan berdasarkan Bidang -> Sub Bidang -> Custom Order
        $members = $query->orderBy('bidang')
                         ->orderBy('sub_bidang')
                         ->orderBy('sort_order', 'asc')
                         ->get();
        
        // Kirim daftar bidang untuk filter di view index
        return view('admin.organization.index', [
            'members' => $members,
            'bidangList' => $this->fixedBidang,
            'currentBidang' => $bidang
        ]);
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
            'position' => 'required',
            'bidang' => 'required', 
            'sub_bidang' => 'required', 
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // --- PANGGIL FUNGSI NORMALISASI DI SINI ---
        $data['sub_bidang'] = $this->normalizeSubBidang($request->sub_bidang);
        // ------------------------------------------

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/organization', 'public');
        }

        OrganizationMember::create($data);

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
            'position' => 'required',
            'bidang' => 'required',
            'sub_bidang' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // --- PANGGIL FUNGSI NORMALISASI DI SINI JUGA ---
        $data['sub_bidang'] = $this->normalizeSubBidang($request->sub_bidang);
        // -----------------------------------------------

        if ($request->hasFile('image')) {
            if ($member->image) Storage::disk('public')->delete($member->image);
            $data['image'] = $request->file('image')->store('uploads/organization', 'public');
        }

        $member->update($data);

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