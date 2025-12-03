<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Jangan lupa import Auth

class AnnouncementController extends Controller
{
    // Daftar Semua Kategori yang Ada
    private $allCategories = [
        'Pengumuman Gereja', 
        'Paroki', 
        'Wilayah', 
        'Lingkungan', 
        'OMK', 
        'Misdinar', 
        'PIA/PIR', 
        'Calon Manten', 
        'Berita Duka'
    ];

    /**
     * Helper: Filter Kategori Berdasarkan Role
     */
    private function getAllowedCategories()
    {
        $userRole = Auth::user()->role;

        // 1. ADMIN & PENGURUS: Bisa pilih semua kategori
        if (in_array($userRole, ['admin', 'pengurus_gereja'])) {
            return $this->allCategories;
        }

        // 2. ROLE SPESIFIK: Hanya bisa pilih kategori miliknya sendiri
        $map = [
            'omk'            => ['OMK'],
            'pia_pir'        => ['PIA/PIR'],
            'misdinar'       => ['Misdinar'],
            'lektor'         => ['Lektor'], // Jika Lektor boleh buat pengumuman
            'direktur_musik' => ['Pengumuman Gereja'], // Atau kategori lain yang relevan
        ];

        return $map[$userRole] ?? []; // Default kosong jika tidak punya hak
    }

    public function index(Request $request)
    {
        // 1. Ambil daftar kategori yang DIIZINKAN untuk user ini
        $allowedCategories = $this->getAllowedCategories();

        // 2. Mulai Query
        $query = Announcement::query();

        // 3. TERAPKAN FILTER ROLE (PENTING)
        // Hanya tampilkan pengumuman yang kategorinya ada dalam daftar izin user
        $query->whereIn('category', $allowedCategories);

        // 4. Filter Tambahan dari Input Search/Dropdown (Opsional)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 5. Ambil Data
        $announcements = $query->latest()->paginate(10);

        // 6. Kirim data dan daftar kategori ke View
        return view('admin.announcements.index', compact('announcements', 'allowedCategories'));
    }

    public function create()
    {
        // Kirim daftar kategori yang DIIZINKAN ke view
        $categories = $this->getAllowedCategories();
        
        return view('admin.announcements.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Ambil kategori yang valid untuk user ini
        $allowedCategories = $this->getAllowedCategories();

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            // Validasi: Kategori yang dipilih WAJIB ada di daftar yang diizinkan
            'category' => ['required', \Illuminate\Validation\Rule::in($allowedCategories)],
            'event_date' => 'required|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('uploads/announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Kirim daftar kategori yang DIIZINKAN ke view edit juga
        $categories = $this->getAllowedCategories();

        return view('admin.announcements.edit', compact('announcement', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $allowedCategories = $this->getAllowedCategories();

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            // Validasi kategori lagi saat update
            'category' => ['required', \Illuminate\Validation\Rule::in($allowedCategories)],
            'event_date' => 'required|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $data['image_path'] = $request->file('image')->store('uploads/announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Opsional: Cek hak akses sebelum hapus (misal OMK tidak boleh hapus pengumuman Paroki)
        $announcement = Announcement::findOrFail($id);
        
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman dihapus!');
    }
}