<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\SiteSetting;
use App\Models\Announcement;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $banners = \App\Models\Banner::where('is_active', true)->latest()->get();
        
        // PERBAIKAN DISINI:
        // Menggunakan orderBy('event_date', 'desc') 
        // Artinya: Tanggal acara yang paling baru/masa depan akan tampil paling atas.
        $announcements = \App\Models\Announcement::orderBy('event_date', 'desc')
                            ->take(3)
                            ->get();

        return view('home', compact('banners', 'announcements'));
    }

    // Fungsi halaman lain
    public function sejarah() { return view('pages.sejarah'); }
    public function pengumuman(Request $request)
    {
        // Mulai Query
        $query = Announcement::query();

        // 1. Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // 2. Logika Filter Kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // 3. Urutkan berdasarkan Tanggal Acara (Terbaru di atas)
        // 4. Pagination (9 item per halaman)
        $announcements = $query->orderBy('event_date', 'desc')->paginate(9);

        // Kirim data ke view, sekalian kirim filter yang sedang aktif agar input tidak reset
        return view('pages.pengumuman', [
            'announcements' => $announcements,
            'currentSearch' => $request->search,
            'currentCategory' => $request->category
        ]);
    }

    public function detailPengumuman($id)
    {
        // 1. Ambil data pengumuman berdasarkan ID
        // Jika tidak ketemu, otomatis halaman 404
        $announcement = \App\Models\Announcement::findOrFail($id);

        // 2. Ambil beberapa pengumuman lain untuk Sidebar (Berita Terkait)
        // Kecuali pengumuman yang sedang dibuka
        $others = \App\Models\Announcement::where('id', '!=', $id)
                    ->orderBy('event_date', 'desc')
                    ->take(5)
                    ->get();

        return view('pages.detail-pengumuman', compact('announcement', 'others'));
    }


    public function teritorial()
    {
        // Ambil semua wilayah beserta data lingkungannya
        $territories = \App\Models\Territory::with('lingkungans')->get();
        
        // Hitung total lingkungan untuk statistik
        $totalLingkungan = \App\Models\Lingkungan::count();

        return view('pages.teritorial', compact('territories', 'totalLingkungan'));
    }
    public function showTeritorial($slug)
    {
        // Cari wilayah berdasarkan slug, load juga data lingkungannya
        $territory = \App\Models\Territory::where('slug', $slug)->with('lingkungans')->firstOrFail();
        
        return view('pages.detail-teritorial', compact('territory'));
    }
    public function organisasi() { return view('pages.organisasi'); }
}