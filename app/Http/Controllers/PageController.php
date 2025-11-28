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
    public function pengumuman() { return view('pages.pengumuman'); }
    public function teritorial() { return view('pages.teritorial'); }
    public function organisasi() { return view('pages.organisasi'); }
}