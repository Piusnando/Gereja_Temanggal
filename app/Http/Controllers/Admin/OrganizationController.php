<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lingkungan;
use Illuminate\Http\Request;
use App\Models\OrganizationMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    // Daftar Kategori Organisasi (Disimpan di properti agar tidak ditulis ulang)
    private $categories = [
        'Pengurus Gereja', 
        'OMK', 
        'Misdinar', 
        'KOMSOS', 
        'PIA/PIR', 
        'Mazmur', 
        'Lektor'
    ];

    public function index(Request $request)
    {
        // Default kategori jika tidak ada di URL
        $category = $request->query('category', 'Pengurus Gereja');
        
        $members = OrganizationMember::with('lingkungan')
                    ->where('category', $category)
                    ->orderBy('created_at', 'asc')
                    ->paginate(10);

        // PERBAIKAN: Kirim variabel $categories ke view
        return view('admin.organization.index', [
            'members' => $members,
            'category' => $category,
            'categories' => $this->categories // <--- INI SOLUSINYA
        ]);
    }

    public function create(Request $request)
    {
        $category = $request->query('category');
        $lingkungans = Lingkungan::all();
        
        // PERBAIKAN: Kirim variabel $categories ke view
        return view('admin.organization.create', [
            'lingkungans' => $lingkungans,
            'category' => $category,
            'categories' => $this->categories // <--- INI SOLUSINYA
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'position' => 'required',
            'lingkungan_id' => 'required',
            'image' => 'nullable|image|max:2048' // Validasi Foto (Max 2MB)
        ]);

        $data = $request->all();

        // 1. Logika Upload Foto
        if ($request->hasFile('image')) {
            // Simpan ke folder public/storage/organization
            $data['image'] = $request->file('image')->store('organization', 'public');
        }

        OrganizationMember::create($data);

        return redirect()->route('admin.organization.index', ['category' => $request->category])
                        ->with('success', 'Anggota berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $member = OrganizationMember::findOrFail($id);
        $cat = $member->category;

        // 2. Hapus foto dari penyimpanan jika ada
        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();
        
        return redirect()->route('admin.organization.index', ['category' => $cat])
                        ->with('success', 'Data berhasil dihapus');
    }
}