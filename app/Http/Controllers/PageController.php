<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\SiteSetting;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\OrganizationMember;

class PageController extends Controller
{
    public function home()
    {
        $banners = \App\Models\Banner::where('is_active', true)->latest()->get();
        
        // Pengumuman terbaru
        $announcements = \App\Models\Announcement::orderBy('event_date', 'desc')
                            ->take(3)
                            ->get();

        // TAMBAHAN: Ambil data Wilayah untuk ditampilkan di box teritorial
        // Kita ambil semua wilayah (karena cuma ada 4)
        $territories = \App\Models\Territory::all();

        return view('home', compact('banners', 'announcements', 'territories'));
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
        $announcements = $query->orderBy('event_date', 'desc')->paginate(9);

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

    public function jadwalPetugas() {
        $schedules = \App\Models\LiturgySchedule::query()
                        // Gunakan whereDate agar membandingkan TANGGAL saja, bukan Jam/Detik.
                        // Jadi Misa tadi pagi masih tetap muncul hari ini.
                        ->whereDate('event_at', '>=', now()) 
                        
                        // ATAU: Jika ingin melihat SEMUA jadwal (termasuk masa lalu) untuk tes, 
                        // Hapus baris whereDate di atas.
                        
                        ->with(['assignments.personnel.lingkungan', 'assignments.lingkungan'])
                        ->orderBy('event_at', 'asc') // Urutkan dari yang terdekat
                        ->get();

        return view('pages.jadwal-petugas', compact('schedules'));
    }

    public function showPetugasRole($role)
    {
        // Ambil jadwal MASA DEPAN yang memiliki petugas dengan peran tersebut
        $schedules = \App\Models\LiturgySchedule::where('event_at', '>=', now())
            ->whereHas('assignments', function($q) use ($role) {
                $q->where('role', $role);
            })
            ->with(['assignments' => function($q) use ($role) {
                $q->where('role', $role)->with(['personnel.lingkungan', 'lingkungan']);
            }])
            ->orderBy('event_at', 'asc')
            ->get();

        return view('pages.petugas-role', compact('schedules', 'role'));
    }

    public function showOrganization($category)
    {
        // Decode URL
        $categoryName = urldecode($category);

        // Ambil anggota berdasarkan kategori
        // Pastikan Anda sudah membuat Model OrganizationMember dan migrasinya
        $members = OrganizationMember::where('category', $categoryName)
                    ->with('lingkungan') // Pastikan relasi 'lingkungan' ada di model OrganizationMember
                    ->orderBy('id', 'asc') // Urutkan berdasarkan ID atau kriteria lain
                    ->get();

        return view('pages.organisasi', compact('members', 'categoryName'));
    }
}