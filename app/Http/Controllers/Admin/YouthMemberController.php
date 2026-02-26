<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YouthMember;
use App\Models\Lingkungan;
use Illuminate\Http\Request;

class YouthMemberController extends Controller
{
    // Menampilkan Daftar Anggota (Bisa PIA atau OMK tergantung parameter URL)
    public function index(Request $request)
    {
        $category = $request->query('category', 'OMK'); // Default OMK jika tidak ada
        
        $members = YouthMember::with('lingkungan')
                    ->where('category', $category)
                    ->orderBy('name')
                    ->paginate(15);

        return view('admin.youth.members.index', compact('members', 'category'));
    }

    // Form Tambah
    public function create(Request $request)
    {
        $category = $request->query('category', 'OMK');
        $lingkungans = Lingkungan::orderBy('name')->get();
        
        return view('admin.youth.members.create', compact('lingkungans', 'category'));
    }

    // Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:PIA,PIR,OMK',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
        ]);

        YouthMember::create($request->all());

        return redirect()->route('admin.youth.members.index', ['category' => $request->category])
                         ->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Form Edit
    public function edit($id)
    {
        $member = YouthMember::findOrFail($id);
        $lingkungans = Lingkungan::orderBy('name')->get();
        
        return view('admin.youth.members.edit', compact('member', 'lingkungans'));
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $member = YouthMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        // Handle checkbox is_active (jika tidak dicentang, kirim 0)
        $data['is_active'] = $request->has('is_active');

        $member->update($data);

        return redirect()->route('admin.youth.members.index', ['category' => $member->category])
                         ->with('success', 'Data anggota diperbarui.');
    }

    // Hapus Data
    public function destroy($id)
    {
        $member = YouthMember::findOrFail($id);
        $category = $member->category; // Simpan kategori sebelum dihapus untuk redirect
        $member->delete();

        return redirect()->route('admin.youth.members.index', ['category' => $category])
                         ->with('success', 'Anggota dihapus.');
    }
}