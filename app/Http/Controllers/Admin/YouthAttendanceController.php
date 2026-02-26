<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\YouthMember;
use App\Models\YouthAttendance;
use Illuminate\Http\Request;

class YouthAttendanceController extends Controller
{
    /**
     * Halaman Input Presensi
     */
    public function create(Request $request)
    {
        // 1. Filter Kategori (PIA/OMK)
        $category = $request->query('category', 'OMK'); // Default OMK

        // 2. Ambil Kegiatan (Hanya yang terbaru agar dropdown tidak kepanjangan)
        // Kita ambil 20 kegiatan terakhir
        $activities = Activity::orderBy('start_time', 'desc')->take(20)->get();
        
        // 3. Ambil ID Kegiatan yang sedang dipilih (jika ada)
        $selectedActivityId = $request->query('activity_id');

        // 4. Ambil Anggota (Hanya yang Aktif & Sesuai Kategori)
        $members = YouthMember::where('is_active', true)
                    ->where('category', $category)
                    ->with('lingkungan')
                    ->orderBy('name')
                    ->get();

        // 5. Cek Data Presensi Lama (Jika sedang edit presensi)
        // Array ID member yang sudah tercatat hadir di kegiatan ini
        $attendanceIds = [];
        if ($selectedActivityId) {
            $attendanceIds = YouthAttendance::where('activity_id', $selectedActivityId)
                                ->pluck('youth_member_id')
                                ->toArray();
        }

        return view('admin.youth.attendance.create', compact(
            'category', 
            'activities', 
            'members', 
            'selectedActivityId', 
            'attendanceIds'
        ));
    }

    /**
     * Proses Simpan Presensi
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'attendees' => 'array', // Array ID member yang hadir
            'attendees.*' => 'exists:youth_members,id',
            'category_filter' => 'required' // Untuk redirect balik
        ]);

        $activityId = $request->activity_id;
        $presentMemberIds = $request->input('attendees', []); // List ID yang dicentang
        $category = $request->category_filter;

        // LOGIKA SYNC (Hapus yang tidak dicentang, Tambah yang dicentang)
        // 1. Ambil semua member di kategori ini (agar tidak menghapus presensi kategori lain di kegiatan yang sama)
        $allMemberIdsInCategory = YouthMember::where('category', $category)->pluck('id');

        // 2. Hapus data presensi lama untuk kategori ini di event ini
        YouthAttendance::where('activity_id', $activityId)
            ->whereIn('youth_member_id', $allMemberIdsInCategory)
            ->delete();

        // 3. Masukkan data baru (Bulk Insert agar cepat)
        $insertData = [];
        $now = now();
        foreach ($presentMemberIds as $memberId) {
            $insertData[] = [
                'activity_id' => $activityId,
                'youth_member_id' => $memberId,
                'status' => 'hadir',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($insertData)) {
            YouthAttendance::insert($insertData);
        }

        return redirect()->route('admin.youth.attendance.create', [
            'category' => $category, 
            'activity_id' => $activityId
        ])->with('success', 'Data presensi berhasil disimpan (' . count($insertData) . ' Hadir).');
    }
}