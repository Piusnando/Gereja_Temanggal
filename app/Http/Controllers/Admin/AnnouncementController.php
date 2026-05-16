<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Lingkungan;
use App\Models\Territory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    use AuthorizesRequests; 

    private $allCategories = [
        'Pengumuman Gereja', 'Paroki', 'Wilayah', 'Lingkungan', 
        'OMK', 'Misdinar', 'PIA/PIR', 'Calon Manten', 'Berita Duka'
    ];

    private function getAllowedCategories()
    {
        $userRole = Auth::user()->role;

        // Admin, Pengurus, Koster bebas
        if (in_array($userRole, ['admin', 'pengurus_gereja', 'koster'])) {
            return $this->allCategories;
        }

        // ROLE SPESIFIK: Batasi dropdown kategori yang muncul
        $map = [
            'ketua_wilayah'  => ['Wilayah', 'Lingkungan'], // Ketua Wilayah boleh buat untuk wilayahnya atau 1 lingkungan di bawahnya
            'ketua_lingkungan'=> ['Lingkungan'],
            'omk'            => ['OMK'],
            'pia_pir'        => ['PIA/PIR'],
            'misdinar'       => ['Misdinar'],
            'lektor'         => ['Lektor'],
            'direktur_musik' => ['Pengumuman Gereja'],
        ];

        return $map[$userRole] ?? [];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        // Load relasi agar nama wilayah & lingkungan bisa dipanggil
        $query = Announcement::with(['territory', 'lingkungan']);

        // Filter berdasarkan hak akses
        if ($user->role === 'ketua_wilayah') {
            $query->where('territory_id', $user->territory_id);
        } elseif ($user->role === 'ketua_lingkungan') {
            $query->where('lingkungan_id', $user->lingkungan_id);
        } elseif (!in_array($user->role, ['admin', 'pengurus_gereja', 'koster'])) {
            $allowed = $this->getAllowedCategories();
            if(!empty($allowed)) {
                $query->whereIn('category', $allowed);
            }
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $announcements = $query->latest()->paginate(10);
        $allowedCategories = $this->getAllowedCategories();

        return view('admin.announcements.index', compact('announcements', 'allowedCategories'));
    }

    public function create()
    {
        $categories = $this->getAllowedCategories();
        $user = Auth::user();
        
        // Siapkan data wilayah untuk dropdown (hanya yang diawasi jika ketua wilayah)
        if ($user->role === 'ketua_wilayah') {
            $territories = Territory::where('id', $user->territory_id)->with('lingkungans')->get();
        } else {
            $territories = Territory::with('lingkungans')->orderBy('name')->get();
        }

        return view('admin.announcements.create', compact('categories', 'territories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'territory_id' => 'nullable|exists:territories,id',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);
        
        $user = Auth::user();
        $data['is_pinned'] = $request->has('is_pinned');

        // Otomatis tandai pemilik pengumuman berdasarkan role
        if ($user->role === 'ketua_lingkungan') {
            $data['lingkungan_id'] = $user->lingkungan_id;
            $data['territory_id'] = $user->lingkungan->territory_id;
            $data['category'] = 'Lingkungan';
        } 
        elseif ($user->role === 'ketua_wilayah') {
            $data['territory_id'] = $user->territory_id;
            // Jika dia milih lingkungan spesifik, simpan. Jika tidak, kosongkan.
            if ($request->filled('lingkungan_id')) {
                $isOwnLingkungan = Lingkungan::where('id', $request->lingkungan_id)->where('territory_id', $user->territory_id)->exists();
                $data['lingkungan_id'] = $isOwnLingkungan ? $request->lingkungan_id : null;
                $data['category'] = 'Lingkungan';
            } else {
                $data['lingkungan_id'] = null;
                $data['category'] = 'Wilayah';
            }
        } 
        else {
            // Jika yang login Admin, reset nilai id jika bukan kategori wilayah/lingkungan
            if (!in_array($data['category'], ['Wilayah', 'Lingkungan'])) {
                $data['territory_id'] = null;
                $data['lingkungan_id'] = null;
            }
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('uploads/announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);
        $categories = $this->getAllowedCategories();
        
        $user = Auth::user();
        if ($user->role === 'ketua_wilayah') {
            $territories = Territory::where('id', $user->territory_id)->with('lingkungans')->get();
        } else {
            $territories = Territory::with('lingkungans')->orderBy('name')->get();
        }

        return view('admin.announcements.edit', compact('announcement', 'categories', 'territories'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'territory_id' => 'nullable|exists:territories,id',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);

        $user = Auth::user();
        $data['is_pinned'] = $request->has('is_pinned');

        if ($user->role === 'ketua_lingkungan') {
            $data['lingkungan_id'] = $user->lingkungan_id;
            $data['territory_id'] = $user->lingkungan->territory_id;
            $data['category'] = 'Lingkungan';
        } 
        elseif ($user->role === 'ketua_wilayah') {
            $data['territory_id'] = $user->territory_id;
            if ($request->filled('lingkungan_id')) {
                $isOwnLingkungan = Lingkungan::where('id', $request->lingkungan_id)->where('territory_id', $user->territory_id)->exists();
                $data['lingkungan_id'] = $isOwnLingkungan ? $request->lingkungan_id : null;
                $data['category'] = 'Lingkungan';
            } else {
                $data['lingkungan_id'] = null;
                $data['category'] = 'Wilayah';
            }
        } 
        else {
            if (!in_array($data['category'], ['Wilayah', 'Lingkungan'])) {
                $data['territory_id'] = null;
                $data['lingkungan_id'] = null;
            }
        }

        if ($request->hasFile('image')) {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $data['image_path'] = $request->file('image')->store('uploads/announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);
        if ($announcement->image_path) Storage::disk('public')->delete($announcement->image_path);
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}