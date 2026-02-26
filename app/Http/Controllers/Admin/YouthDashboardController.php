<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YouthMember;
use App\Models\Activity; // Ganti YouthEvent jadi Activity
use Illuminate\Http\Request;
use Carbon\Carbon;

class YouthDashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Kategori
        $baseQuery = YouthMember::where('is_active', true);
        $stats = [
            'pia'   => (clone $baseQuery)->where('category', 'PIA')->count(),
            'pir'   => (clone $baseQuery)->where('category', 'PIR')->count(),
            'omk'   => (clone $baseQuery)->where('category', 'OMK')->count(),
            'total' => $baseQuery->count(),
        ];

        // 2. Tren Kehadiran (Menggunakan Activity)
        $recentEvents = Activity::withCount(['attendances' => function($q) {
                $q->where('status', 'hadir');
            }])
            // Hanya ambil activity yang punya presensi (agar kegiatan umum tidak mengganggu grafik)
            ->has('attendances') 
            ->orderBy('start_time', 'desc') // start_time adalah kolom di tabel activities
            ->take(5)
            ->get()
            ->sortBy('start_time');

        // 3. Deteksi Anggota "Pasif"
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        $passiveMembers = YouthMember::with('lingkungan')
            ->where('is_active', true)
            ->whereDoesntHave('attendances', function($q) use ($threeMonthsAgo) {
                // Cek kehadiran di activity
                $q->whereHas('activity', function($sq) use ($threeMonthsAgo) {
                    $sq->where('start_time', '>=', $threeMonthsAgo);
                });
            })
            ->take(5)
            ->get();

        // 4. Top Rajin
        $topMembers = YouthMember::with('lingkungan')
            ->withCount(['attendances' => function($q) {
                $q->where('status', 'hadir');
            }])
            ->orderBy('attendances_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.youth.dashboard', compact('stats', 'recentEvents', 'passiveMembers', 'topMembers'));
    }
}