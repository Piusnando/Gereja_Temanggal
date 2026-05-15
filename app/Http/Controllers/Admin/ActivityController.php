<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use App\Models\Lingkungan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Wajib ditambahkan untuk ngecek User

class ActivityController extends Controller
{
    // Fungsi Bantuan untuk menerjemahkan Role ke Nama Penyelenggara
    private function getAllowedOrganizer() {
        $role = Auth::user()->role;
        $map = [
            'omk'            => 'OMK',
            'pia_pir'        => 'PIA/PIR',
            'misdinar'       => 'Misdinar',
            'lektor'         => 'Lektor',
            'koster'         => 'Koster',
            'direktur_musik' => 'Paduan Suara/Musik'
        ];
        return $map[$role] ?? 'Umum';
    }

    public function index()
    {
        $query = Activity::query();
        $role = Auth::user()->role;

        // Jika bukan admin/pengurus, filter data HANYA miliknya saja
        if (!in_array($role, ['admin', 'pengurus_gereja'])) {
            $org = $this->getAllowedOrganizer();
            $query->where('organizer', $org);
        }

        $activities = $query->latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        $lingkungans = Lingkungan::orderBy('name')->get();
        $myOrg = $this->getAllowedOrganizer(); // Kirim nama organisasinya ke view

        return view('admin.activities.create', compact('lingkungans', 'myOrg'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'organizer' => 'nullable|string|max:255', // Ubah jadi nullable krn akan kita set otomatis
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
        ]);

        $data = $request->except(['image', 'organizer']); // Pisahkan gambar & organizer
        $data['show_on_lingkungan_page'] = $request->has('show_on_lingkungan_page');

        if (empty($request->input('lingkungan_id'))) {
            $data['lingkungan_id'] = null;
        }

        // AUTO SET PENYELENGGARA BERDASARKAN ROLE
        $role = Auth::user()->role;
        if (in_array($role, ['admin', 'pengurus_gereja']) && $request->filled('organizer')) {
            $data['organizer'] = $request->organizer; // Admin bebas isi apa saja
        } else {
            $data['organizer'] = $this->getAllowedOrganizer(); // Role lain dipaksa sesuai sistem
        }

        // Upload Gambar
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('uploads/activities', 'public');
        }

        Activity::create($data);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Kegiatan baru berhasil ditambahkan.');
    }

    public function edit(Activity $activity)
    {
        $role = Auth::user()->role;
        
        // KEAMANAN GANDA: Cek kalau URL diakses paksa
        if (!in_array($role, ['admin', 'pengurus_gereja'])) {
            if ($activity->organizer !== $this->getAllowedOrganizer()) {
                abort(403, 'Akses Ditolak: Anda tidak berhak mengedit berita/kegiatan bagian lain.');
            }
        }

        $lingkungans = Lingkungan::orderBy('name')->get();
        return view('admin.activities.edit', compact('activity', 'lingkungans'));
    }

    public function update(Request $request, Activity $activity)
    {
        $role = Auth::user()->role;
        
        // KEAMANAN GANDA
        if (!in_array($role, ['admin', 'pengurus_gereja'])) {
            if ($activity->organizer !== $this->getAllowedOrganizer()) {
                abort(403, 'Akses Ditolak: Anda tidak berhak mengedit kegiatan ini.');
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'organizer' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);

        $data = $request->except(['image', 'organizer']);
        $data['show_on_lingkungan_page'] = $request->has('show_on_lingkungan_page');

        // AUTO SET PENYELENGGARA
        if (in_array($role, ['admin', 'pengurus_gereja']) && $request->filled('organizer')) {
            $data['organizer'] = $request->organizer;
        } else {
            $data['organizer'] = $this->getAllowedOrganizer();
        }

        // Gambar
        if ($request->hasFile('image')) {
            if ($activity->image_path) {
                Storage::disk('public')->delete($activity->image_path);
            }
            $data['image_path'] = $request->file('image')->store('uploads/activities', 'public');
        }
        
        if ($request->input('lingkungan_id') === '') {
            $data['lingkungan_id'] = null;
        }

        $activity->update($data); // Menyimpan pembaruan

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        $role = Auth::user()->role;
        
        // KEAMANAN GANDA UNTUK HAPUS
        if (!in_array($role, ['admin', 'pengurus_gereja'])) {
            if ($activity->organizer !== $this->getAllowedOrganizer()) {
                return back()->with('error', 'Gagal: Anda tidak berhak menghapus kegiatan kelompok lain!');
            }
        }

        if ($activity->image_path) {
            Storage::disk('public')->delete($activity->image_path);
        }

        $activity->delete();

        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}