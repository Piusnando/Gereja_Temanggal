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
    // Helper: Menentukan kategori yang boleh diakses user
    private function getAllowedCategories()
    {
        $role = Auth::user()->role;

        // Admin & Pengurus boleh semua
        if (in_array($role, ['admin', 'pengurus_gereja'])) {
            return ['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor', 'Paduan Suara'];
        }

        // Role Spesifik
        if ($role == 'omk') return ['OMK'];
        if ($role == 'misdinar') return ['Misdinar'];
        if ($role == 'lektor') return ['Lektor'];
        if ($role == 'pia_pir') return ['PIA & PIR'];
        if ($role == 'direktur_musik') return ['Mazmur', 'Organis', 'Paduan Suara']; // Musik pegang 3 ini

        return []; // Default kosong
    }

    public function index(Request $request)
    {
        $allowed = $this->getAllowedCategories();
        
        // 1. Ambil Kategori yang sedang dipilih dari URL (agar tidak undefined di view)
        $category = $request->query('category'); 

        // Filter Query berdasarkan hak akses
        $query = OrganizationMember::whereIn('category', $allowed);

        // Filter tambahan dari dropdown (jika user memilih kategori tertentu)
        if ($category && $category != '') {
            $query->where('category', $category);
        }

        $members = $query->latest()->paginate(10);
        
        // 2. JANGAN LUPA: Kirim variabel '$category' ke view di dalam compact
        return view('admin.organization.index', compact('members', 'allowed', 'category'));
    }

    public function create()
    {
        $allowed = $this->getAllowedCategories();
        $lingkungans = Lingkungan::all();
        return view('admin.organization.create', compact('lingkungans', 'allowed'));
    }

    public function store(Request $request)
    {
        $allowed = $this->getAllowedCategories();

        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'category' => 'required|in:' . implode(',', $allowed), // Validasi: Hanya boleh input kategori haknya
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/organization', 'public');
        }

        OrganizationMember::create($data);

        return redirect()->route('admin.organization.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $member = OrganizationMember::findOrFail($id);
        $allowed = $this->getAllowedCategories();

        // Cek Hak Akses: User tidak boleh edit data di luar kategorinya
        if (!in_array($member->category, $allowed)) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit kategori ini.');
        }

        $lingkungans = Lingkungan::all();
        return view('admin.organization.edit', compact('member', 'lingkungans', 'allowed'));
    }

    public function update(Request $request, $id)
    {
        $member = OrganizationMember::findOrFail($id);
        $allowed = $this->getAllowedCategories();

        // Cek Hak Akses
        if (!in_array($member->category, $allowed)) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'category' => 'required|in:' . implode(',', $allowed),
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

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
        $allowed = $this->getAllowedCategories();

        if (!in_array($member->category, $allowed)) {
            abort(403, 'Anda tidak berhak menghapus data ini.');
        }

        if ($member->image) Storage::disk('public')->delete($member->image);
        $member->delete();

        return back()->with('success', 'Anggota dihapus');
    }
}