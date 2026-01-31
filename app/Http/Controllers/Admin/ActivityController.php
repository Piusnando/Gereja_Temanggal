<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Menampilkan daftar semua kegiatan.
     */
    public function index()
    {
        // Ambil data terbaru, paginasi 10 per halaman
        $activities = Activity::latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Menampilkan form untuk membuat kegiatan baru.
     */
    public function create()
    {
        return view('admin.activities.create');
    }

    /**
     * Menyimpan data kegiatan baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'organizer' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
        ]);

        // 2. Siapkan Data
        $data = $request->only([
            'title', 'description', 'organizer', 'start_time', 'end_time', 'location'
        ]);

        // 3. Handle Upload Gambar
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/activities', 'public');
            $data['image_path'] = $path;
        }

        // 4. Simpan ke Database
        Activity::create($data);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Kegiatan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kegiatan.
     */
    public function edit(Activity $activity)
    {
        // Laravel's Route Model Binding akan otomatis mencari Activity berdasarkan ID
        return view('admin.activities.edit', compact('activity'));
    }

    /**
     * Mengupdate data kegiatan di database.
     */
    public function update(Request $request, Activity $activity)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'organizer' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
        ]);

        // 2. Siapkan Data
        $data = $request->only([
            'title', 'description', 'organizer', 'start_time', 'end_time', 'location'
        ]);

        // 3. Handle Upload Gambar Baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($activity->image_path) {
                Storage::disk('public')->delete($activity->image_path);
            }
            
            // Simpan gambar baru
            $path = $request->file('image')->store('uploads/activities', 'public');
            $data['image_path'] = $path;
        }

        // 4. Update Database
        $activity->update($data);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    /**
     * Menghapus data kegiatan dari database.
     */
    public function destroy(Activity $activity)
    {
        // Hapus gambar terkait dari storage
        if ($activity->image_path) {
            Storage::disk('public')->delete($activity->image_path);
        }

        // Hapus data dari tabel
        $activity->delete();

        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}