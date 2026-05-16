<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use App\Models\Lingkungan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class ActivityController extends Controller
{
    use AuthorizesRequests; 

    private function getAllowedOrganizer() {
        $role = Auth::user()->role;
        $map = [
            'omk' => 'OMK', 'pia_pir' => 'PIA/PIR', 'misdinar' => 'Misdinar',
            'lektor' => 'Lektor', 'koster' => 'Koster', 'direktur_musik' => 'Paduan Suara/Musik'
        ];
        return $map[$role] ?? 'Umum';
    }

    public function index()
    {
        $user = Auth::user();
        $query = Activity::query();

        // =========================================================================
        // LOGIKA FILTER BERDASARKAN ROLE PENGGUNA (DIPERKETAT)
        // =========================================================================

        // 1. Jika yang login adalah Ketua Wilayah
        if ($user->role === 'ketua_wilayah') {
            // Ambil semua ID lingkungan yang ada di bawah wilayahnya
            $lingkunganIds = Lingkungan::where('territory_id', $user->territory_id)->pluck('id');
            // Format nama organizer untuk kegiatan tingkat wilayah
            $namaWilayah = 'Wilayah ' . $user->territory->name;

            $query->where(function ($q) use ($lingkunganIds, $namaWilayah) {
                // Tampilkan kegiatan milik lingkungan di bawahnya
                $q->whereIn('lingkungan_id', $lingkunganIds)
                  // ATAU tampilkan kegiatan yang murni dibuat atas nama Wilayah ini
                  ->orWhere('organizer', $namaWilayah);
            });
        } 
        
        // 2. Jika yang login adalah Ketua Lingkungan
        elseif ($user->role === 'ketua_lingkungan') {
            // SANGAT KETAT: Hanya tampilkan yang ID lingkungannya persis sama
            $query->where('lingkungan_id', $user->lingkungan_id);
        } 
        
        // 3. Jika yang login adalah role spesifik (OMK, Misdinar, dll)
        elseif (!in_array($user->role, ['admin', 'pengurus_gereja'])) {
            $org = $this->getAllowedOrganizer();
            $query->where('organizer', $org);
        }
        
        // 4. Admin dan Pengurus Gereja otomatis lewat sini (melihat semua tanpa filter)

        // =========================================================================

        $activities = $query->latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        $user = Auth::user();
        $lingkungans = collect(); // Default koleksi kosong

        if (in_array($user->role, ['admin', 'pengurus_gereja'])) {
            $lingkungans = Lingkungan::orderBy('name')->get();
        } elseif ($user->role === 'ketua_wilayah') {
            $lingkungans = Lingkungan::where('territory_id', $user->territory_id)->orderBy('name')->get();
        } elseif ($user->role === 'ketua_lingkungan') {
            $lingkungans = Lingkungan::where('id', $user->lingkungan_id)->get();
        }

        $myOrg = $this->getAllowedOrganizer();
        return view('admin.activities.create', compact('lingkungans', 'myOrg'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255', 'description' => 'required',
            'image' => 'nullable|image|max:2048', 'organizer' => 'nullable|string',
            'start_time' => 'required|date', 'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'required|string', 'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);
        
        $data['show_on_lingkungan_page'] = $request->has('show_on_lingkungan_page');
        $user = Auth::user();

        // Logika Penentuan Organizer & Lingkungan berdasarkan Role
        if ($user->role === 'ketua_lingkungan') {
            $data['lingkungan_id'] = $user->lingkungan_id;
            $data['organizer'] = 'Lingkungan ' . $user->lingkungan->name;
        } elseif ($user->role === 'ketua_wilayah') {
            $data['organizer'] = 'Wilayah ' . $user->territory->name;
            // Hanya simpan lingkungan_id jika valid milik wilayahnya
            if ($request->filled('lingkungan_id')) {
                $isOwnLingkungan = Lingkungan::where('id', $request->lingkungan_id)->where('territory_id', $user->territory_id)->exists();
                $data['lingkungan_id'] = $isOwnLingkungan ? $request->lingkungan_id : null;
            } else {
                 $data['lingkungan_id'] = null;
            }
        } elseif (!in_array($user->role, ['admin', 'pengurus_gereja'])) {
            $data['organizer'] = $this->getAllowedOrganizer();
            $data['lingkungan_id'] = null;
        } else { // Admin & Pengurus
            $data['organizer'] = $request->input('organizer', 'Paroki');
            $data['lingkungan_id'] = $request->input('lingkungan_id');
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('uploads/activities', 'public');
        }

        Activity::create($data);

        return redirect()->route('admin.activities.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Activity $activity)
    {
        $this->authorize('update', $activity);
        
        $user = Auth::user();
        $lingkungans = collect();
        if (in_array($user->role, ['admin', 'pengurus_gereja'])) {
            $lingkungans = Lingkungan::orderBy('name')->get();
        } elseif ($user->role === 'ketua_wilayah') {
            $lingkungans = Lingkungan::where('territory_id', $user->territory_id)->orderBy('name')->get();
        } elseif ($user->role === 'ketua_lingkungan') {
            $lingkungans = Lingkungan::where('id', $user->lingkungan_id)->get();
        }
        
        return view('admin.activities.edit', compact('activity', 'lingkungans'));
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorize('update', $activity);
        // Validasi dan logika update disamakan dengan store()
        // ... (Kode sama dengan store(), hanya bagian akhirnya diganti menjadi $activity->update($data)) ...
        $data = $request->validate([ /* ... sama ... */ ]);
        $data['show_on_lingkungan_page'] = $request->has('show_on_lingkungan_page');
        $user = Auth::user();

        // Logika Penentuan Organizer & Lingkungan (copy dari store)
        if ($user->role === 'ketua_lingkungan') { /* ... sama ... */ }
        elseif ($user->role === 'ketua_wilayah') { /* ... sama ... */ }
        elseif (!in_array($user->role, ['admin', 'pengurus_gereja'])) { /* ... sama ... */ }
        else { /* ... sama ... */ }

        if ($request->hasFile('image')) { /* ... sama ... */ }

        $activity->update($data); // <-- Perbedaan utama di sini

        return redirect()->route('admin.activities.index')->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        $this->authorize('delete', $activity);
        if ($activity->image_path) Storage::disk('public')->delete($activity->image_path);
        $activity->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}