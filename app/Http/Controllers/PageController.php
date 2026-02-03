<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Activity;
use App\Models\Territory;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\FacilityBooking;
use App\Service\LiturgiService;
use App\Models\OrganizationMember;

class PageController extends Controller
{
    public function home(LiturgiService $liturgiService)
    {
        $banners = Banner::where('is_active', true)->orderBy('order', 'asc')->latest()->get();
        
        $announcements = Announcement::orderBy('is_pinned', 'desc')
                            ->orderBy('event_date', 'desc')
                            ->take(3)
                            ->get();

        $territories = Territory::all();
        
        $liturgi = $liturgiService->getLiturgiHariIni();
        if (!$liturgi) {
             $liturgi = ['tanggal' => now()->locale('id')->translatedFormat('l, d F Y'), 'warna' => 'Hijau', 'perayaan' => 'Data tidak tersedia', 'bacaan_1' => '-', 'mazmur' => '-', 'injil' => '-'];
        }

        // BERITA KEGIATAN (Tetap ada di Home)
        $activityNews = Activity::orderBy('start_time', 'desc')->take(3)->get();

        // HAPUS BAGIAN $facilityBookings DARI SINI

        return view('home', compact('banners', 'announcements', 'territories', 'liturgi', 'activityNews'));
    }

    // Fungsi halaman lain
    public function sejarah() 
    { 
        // KODE ASLI (Disimpan/Komentar dulu)
        // return view('pages.sejarah'); 

        // KODE SEMENTARA (Tampilkan Coming Soon)
        return view('pages.coming-soon', [
            'title' => 'Sejarah - Segera Hadir',
            'pageName' => 'Sejarah Gereja'
        ]);
    }
    public function pengumuman(Request $request)
    {
        $query = \App\Models\Announcement::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // UPDATE QUERY DISINI JUGA
        $announcements = $query->orderBy('is_pinned', 'desc')
                               ->orderBy('event_date', 'desc')
                               ->paginate(6);

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

    public function kegiatan(Request $request)
    {
        $query = \App\Models\Activity::query();

        // Fitur Search
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('organizer', 'like', '%' . $request->search . '%');
        }

        // Urutkan dari yang paling baru (start_time descending)
        $activities = $query->orderBy('start_time', 'desc')->paginate(9);

        return view('pages.kegiatan', [
            'activities' => $activities,
            'currentSearch' => $request->search
        ]);
    }

    // FUNGSI HALAMAN DETAIL KEGIATAN
    public function detailKegiatan($id)
    {
        $activity = \App\Models\Activity::findOrFail($id);

        // Ambil kegiatan lain (selain ini) untuk sidebar/rekomendasi
        $others = \App\Models\Activity::where('id', '!=', $id)
                    ->latest()
                    ->take(4)
                    ->get();

        return view('pages.detail-kegiatan', compact('activity', 'others'));
    }

    public function jadwalGedung()
    {
        // Ambil jadwal yang akan datang, paginate 15 per halaman
        $bookings = FacilityBooking::where('start_time', '>=', now())
                        ->orderBy('start_time', 'asc')
                        ->paginate(15);

        return view('pages.jadwal-gedung', compact('bookings'));
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
    public function organisasi()
    {
        $members = OrganizationMember::with('lingkungan')
                    ->orderBy('sort_order', 'asc')
                    ->get();

        // Group by 'bidang' (Kolom Baru)
        $groupedMembers = $members->groupBy('bidang');

        // Urutan Tampilan
        $categoriesOrder = [
            'Pengurus Harian', 
            'Tim Pelayanan Bidang Liturgi', 
            'Tim Pelayanan Bidang Sarana dan Prasarana', 
            'Tim Pelayanan Bidang Umum', 
            'Tim Pelayanan Bidang Pewartaan dan Pelayanan'
        ];

        return view('pages.organisasi', compact('groupedMembers', 'categoriesOrder'));
    }

    public function jadwalPetugas() 
    {
        // KODE ASLI (Disimpan/Komentar dulu)
        /*
        $schedules = \App\Models\LiturgySchedule::where('event_at', '>=', now())
                        ->with(['assignments.personnel.lingkungan', 'assignments.lingkungan'])
                        ->orderBy('event_at', 'asc')
                        ->get();
        return view('pages.jadwal-petugas', compact('schedules'));
        */

        // KODE SEMENTARA
        return view('pages.coming-soon', [
            'title' => 'Jadwal Petugas - Segera Hadir',
            'pageName' => 'Jadwal Petugas Liturgi'
        ]);
    }

    public function showPetugasRole($role)
    {
        // KODE ASLI (Disimpan/Komentar dulu)
        /*
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
        */

        // KODE SEMENTARA
        return view('pages.coming-soon', [
            'title' => "Jadwal $role - Segera Hadir",
            'pageName' => "Jadwal $role"
        ]);
    }

    public function showOrganization($category) 
    {
        $bidangName = urldecode($category);

        $members = \App\Models\OrganizationMember::where('bidang', $bidangName)
                    ->with('lingkungan')
                    
                    // 1. Prioritaskan urutan Sub-Tim sesuai settingan Admin
                    ->orderBy('sub_bidang_order', 'asc') 
                    
                    // 2. Jika urutan sama, baru urutkan Abjad (Fallback)
                    ->orderBy('sub_bidang', 'asc') 
                    
                    // 3. Terakhir urutkan Anggotanya
                    ->orderBy('sort_order', 'asc') 
                    
                    ->get()
                    ->groupBy('sub_bidang');

        return view('pages.organisasi', compact('members', 'bidangName'));
    }
    public function showSubOrganization($category, $sub_category)
    {
        // 1. Decode URL (menghilangkan %20 dsb)
        $bidangName = urldecode($category);
        $subName = urldecode($sub_category);

        // 2. Ambil Anggota HANYA dari Bidang & Sub Bidang tersebut
        $members = \App\Models\OrganizationMember::where('bidang', $bidangName)
                    ->where('sub_bidang', $subName)
                    ->with('lingkungan')
                    ->orderBy('sort_order', 'asc')
                    ->get();

        // 3. Jika data tidak ditemukan (user ketik url ngawur), kembalikan ke halaman bidang
        if ($members->isEmpty()) {
            return redirect()->route('organisasi.show', ['category' => $bidangName]);
        }

        // 4. Return ke View Baru
        return view('pages.detail-tim', compact('members', 'bidangName', 'subName'));
    }
}