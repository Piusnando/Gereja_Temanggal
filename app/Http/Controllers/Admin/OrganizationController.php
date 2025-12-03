<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\Lingkungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    // Daftar Lengkap Semua Kategori
    private $allCategories = [
        'Pengurus Gereja', 
        'OMK', 
        'Misdinar', 
        'KOMSOS', 
        'PIA/PIR', 
        'Mazmur', 
        'Lektor'
    ];

    /**
     * Helper: Mendapatkan kategori apa saja yang boleh diakses oleh user yang login
     */
    private function getAllowedCategories()
    {
        $userRole = Auth::user()->role;

        // 1. ADMIN & PENGURUS GEREJA: Bisa Akses Semua
        if (in_array($userRole, ['admin', 'pengurus_gereja'])) {
            return $this->allCategories;
        }

        // 2. LOGIKA ROLE SPESIFIK
        // Format: 'role_di_database' => ['Nama Kategori Organisasi']
        $map = [
            'omk'            => ['OMK'],
            'misdinar'       => ['Misdinar'],
            'lektor'         => ['Lektor'],
            'direktur_musik' => ['Mazmur'], // Dir. Musik pegang Mazmur
            'pia_pir'        => ['PIA/PIR'],
            // Jika ada user role 'komsos', tambahkan di sini
            // 'komsos'      => ['KOMSOS'], 
        ];

        // Kembalikan array kategori yang boleh diakses, atau array kosong jika tidak ada hak
        return $map[$userRole] ?? [];
    }

    public function index(Request $request)
    {
        // 1. Ambil daftar kategori yang DIIZINKAN untuk user ini
        $allowedCategories = $this->getAllowedCategories();

        if (empty($allowedCategories)) {
            abort(403, 'Akun Anda tidak memiliki akses ke menu Organisasi.');
        }

        // 2. Tentukan kategori default (ambil yang pertama dari daftar izin)
        $defaultCategory = $allowedCategories[0];
        
        // 3. Ambil kategori dari URL, jika tidak ada pakai default
        $category = $request->query('category', $defaultCategory);

        // 4. SECURITY CHECK: Cegah user ganti URL ke kategori yang dilarang
        if (!in_array($category, $allowedCategories)) {
            abort(403, 'Akses Ditolak: Anda tidak berhak mengakses data organisasi ' . $category);
        }
        
        $members = OrganizationMember::with('lingkungan')
                    ->where('category', $category)
                    ->orderBy('created_at', 'asc')
                    ->paginate(10);

        // Kirim $allowedCategories sebagai variable $categories ke view
        // Agar tab menu di atas hanya menampilkan yang boleh saja
        return view('admin.organization.index', [
            'members' => $members,
            'category' => $category,
            'categories' => $allowedCategories 
        ]);
    }

    public function create(Request $request)
    {
        $allowedCategories = $this->getAllowedCategories();
        
        // Validasi akses kategori di URL create juga
        $category = $request->query('category');
        if ($category && !in_array($category, $allowedCategories)) {
            abort(403, 'Akses Ditolak.');
        }

        // Jika tidak ada di URL, pakai default pertama
        if (!$category && !empty($allowedCategories)) {
            $category = $allowedCategories[0];
        }

        $lingkungans = Lingkungan::all();
        
        return view('admin.organization.create', [
            'lingkungans' => $lingkungans,
            'category' => $category,
            'categories' => $allowedCategories // Dropdown hanya muncul yg diizinkan
        ]);
    }

    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'position' => 'required',
            'lingkungan_id' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        // SECURITY CHECK: Pastikan kategori yang diinput sesuai hak akses
        $allowedCategories = $this->getAllowedCategories();
        if (!in_array($request->category, $allowedCategories)) {
            return back()->with('error', 'Anda tidak berhak menambahkan anggota ke kategori ini.');
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
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

        // SECURITY CHECK: Pastikan user berhak menghapus data kategori ini
        $allowedCategories = $this->getAllowedCategories();
        if (!in_array($cat, $allowedCategories)) {
            return back()->with('error', 'Akses Ditolak: Anda tidak berhak menghapus data ini.');
        }

        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();
        
        return redirect()->route('admin.organization.index', ['category' => $cat])
                         ->with('success', 'Data berhasil dihapus');
    }

    public function edit($id)
    {
        $member = OrganizationMember::findOrFail($id);
        $lingkungans = Lingkungan::all();
        
        // 1. Ambil daftar kategori yang DIIZINKAN untuk user ini (Security Check)
        $allowedCategories = $this->getAllowedCategories();

        // 2. Jika kategori member ini tidak ada dalam daftar izin user, tolak.
        if (!in_array($member->category, $allowedCategories)) {
            abort(403, 'Akses Ditolak: Anda tidak berhak mengedit data kategori ini.');
        }

        return view('admin.organization.edit', [
            'member' => $member,
            'lingkungans' => $lingkungans,
            'categories' => $allowedCategories
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = OrganizationMember::findOrFail($id);

        // Validasi
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'position' => 'required',
            'lingkungan_id' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        // Security Check lagi saat update
        $allowedCategories = $this->getAllowedCategories();
        if (!in_array($request->category, $allowedCategories) || !in_array($member->category, $allowedCategories)) {
            abort(403, 'Akses Ditolak.');
        }

        $data = $request->all();

        // LOGIKA GANTI FOTO
        if ($request->hasFile('image')) {
            // 1. Hapus foto lama jika ada
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
            // 2. Upload foto baru
            $data['image'] = $request->file('image')->store('organization', 'public');
        }

        $member->update($data);

        return redirect()->route('admin.organization.index', ['category' => $request->category])
                         ->with('success', 'Data anggota berhasil diperbarui');
    }
}