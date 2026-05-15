<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lingkungan;
use Illuminate\Http\Request;
use App\Models\LiturgySchedule;
use App\Models\LiturgyPersonnel;
use App\Models\LiturgyAssignment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Facade untuk Auth

class LiturgyController extends Controller
{
    // =========================================================================
    // BAGIAN 1: DATABASE PETUGAS (PERSONNEL)
    // =========================================================================
    public function personnelIndex(Request $request)
    {
        $type = $request->query('type'); // Ambil dari URL ?type=Misdinar

        // 1. KEAMANAN: Non-Admin tidak boleh melihat "Semua Data"
        if (Auth::user()->role !== 'admin' && !$type) {
            abort(403, 'Akses Ditolak. Anda hanya diizinkan melihat data petugas kategori spesifik.');
        }

        // 2. LOGIKA ADMIN MELIHAT SEMUA (TAMPILAN TERPISAH PER KATEGORI)
        if (!$type) {
            // Tambahkan ->withCount('assignments') di setiap query
            $groupedData = [
                'Misdinar' => LiturgyPersonnel::with('lingkungan')->withCount('assignments')->where('type', 'Misdinar')->orderBy('name')->get(),
                'Lektor'   => LiturgyPersonnel::with('lingkungan')->withCount('assignments')->where('type', 'Lektor')->orderBy('name')->get(),
                'Mazmur'   => LiturgyPersonnel::with('lingkungan')->withCount('assignments')->where('type', 'Mazmur')->orderBy('name')->get(),
                'Organis'  => LiturgyPersonnel::with('lingkungan')->withCount('assignments')->where('type', 'Organis')->orderBy('name')->get(),
            ];

            return view('admin.liturgy.personnels', compact('groupedData', 'type'));
        }

        

        // 3. LOGIKA SATU JENIS SAJA (PAGINATION)
        
        // Pastikan ada ->withCount('assignments')
        $query = LiturgyPersonnel::with('lingkungan')->withCount('assignments'); 
        
        if ($type) {
            $query->where('type', $type);
        }

        $personnels = $query->latest()->paginate(15);
        
        return view('admin.liturgy.personnels', compact('personnels', 'type'));
    }

    public function personnelCreate(Request $request)
    {
        $type = $request->query('type');
        $lingkungans = Lingkungan::all();
        return view('admin.liturgy.personnels_create', compact('lingkungans', 'type'));
    }

    public function personnelStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'lingkungan_id' => 'required_without:is_external',
        ]);

        LiturgyPersonnel::create([
            'name' => $request->name,
            'type' => $request->type,
            'lingkungan_id' => $request->lingkungan_id,
            'is_external' => $request->has('is_external'),
            'external_description' => $request->external_description
        ]);

        return redirect()->route('admin.liturgy.personnels', ['type' => $request->type])
                         ->with('success', 'Data ' . $request->type . ' berhasil disimpan');
    }

    public function personnelDestroy($id)
    {
        $personnel = LiturgyPersonnel::findOrFail($id);
        $userRole = Auth::user()->role;

        // --- SECURITY CHECK (Hanya boleh hapus sesuai bidangnya) ---
        if ($userRole == 'misdinar' && $personnel->type !== 'Misdinar') {
            return back()->with('error', 'Akses Ditolak: Anda hanya boleh menghapus data Misdinar!');
        }
        if ($userRole == 'lektor' && $personnel->type !== 'Lektor') {
            return back()->with('error', 'Akses Ditolak: Anda hanya boleh menghapus data Lektor!');
        }
        if ($userRole == 'direktur_musik' && !in_array($personnel->type, ['Mazmur', 'Organis', 'Paduan Suara'])) {
            return back()->with('error', 'Akses Ditolak: Anda hanya boleh menghapus data petugas musik!');
        }

        $personnel->delete();
        return back()->with('success', 'Data petugas berhasil dihapus.');
    }

    // =========================================================================
    // BAGIAN 2: JADWAL (SCHEDULE)
    // =========================================================================

    public function scheduleIndex()
{
    $userRole = Auth::user()->role;

    // 1. Ambil SEMUA jadwal untuk dimasukkan ke dalam Kalender
    $allSchedules = \App\Models\LiturgySchedule::with(['assignments.personnel.lingkungan', 'assignments.lingkungan'])->get();

    // 2. Ambil jadwal terdekat (Upcoming) untuk list di sidebar kanan
    $upcomingSchedules = \App\Models\LiturgySchedule::where('event_at', '>=', now())
                            ->with(['assignments.personnel.lingkungan', 'assignments.lingkungan'])
                            ->orderBy('event_at', 'asc')
                            ->take(5)
                            ->get();

    // 3. Tentukan hak akses (Siapa yang boleh mendaftarkan tugas apa)
    $allowedRoles =[];
    if (in_array($userRole, ['admin', 'pengurus_gereja', 'koster'])) {
        $allowedRoles =['Misdinar', 'Lektor', 'Mazmur', 'Organis', 'Paduan Suara', 'Parkir'];
    } elseif ($userRole == 'direktur_musik') {
        $allowedRoles =['Mazmur', 'Organis', 'Paduan Suara'];
    } elseif ($userRole == 'misdinar') {
        $allowedRoles = ['Misdinar'];
    } elseif ($userRole == 'lektor') {
        $allowedRoles = ['Lektor'];
    }

    // 4. Ambil semua master data agar siap dipilih di dalam Pop-up nanti
    $personnels = \App\Models\LiturgyPersonnel::with('lingkungan')->orderBy('name')->get();
    $lingkungans = \App\Models\Lingkungan::orderBy('name')->get();

    return view('admin.liturgy.schedules', compact(
        'allSchedules', 'upcomingSchedules', 'allowedRoles', 'personnels', 'lingkungans'
    ));
}

    public function scheduleCreate()
    {
        return view('admin.liturgy.schedules_create');
    }

    public function scheduleStore(Request $request)
    {
        $request->validate(['title'=>'required', 'event_at'=>'required']);
        LiturgySchedule::create($request->all());
        return redirect()->route('admin.liturgy.schedules')->with('success', 'Jadwal Misa berhasil dibuat');
    }

    // 1. TAMPILKAN FORM EDIT
    public function editSchedule($id)
    {
        $schedule = LiturgySchedule::findOrFail($id);
        return view('admin.liturgy.schedules_edit', compact('schedule'));
    }

    // 2. PROSES UPDATE DATA
    public function updateSchedule(Request $request, $id)
    {
        $schedule = LiturgySchedule::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'event_at' => 'required|date'
        ]);

        $schedule->update([
            'title' => $request->title,
            'event_at' => $request->event_at
        ]);

        return redirect()->route('admin.liturgy.schedules')->with('success', 'Jadwal berhasil diperbarui.');
    }

    // 3. PROSES HAPUS JADWAL
    public function destroySchedule($id)
    {
        $schedule = LiturgySchedule::findOrFail($id);
        
        // Data di tabel assignment akan otomatis terhapus (Cascade) jika migrasi database benar
        $schedule->delete();

        return redirect()->route('admin.liturgy.schedules')->with('success', 'Jadwal Misa berhasil dihapus.');
    }


    public function scheduleEdit($id)
    {
        // 1. Ambil Data Jadwal
        $schedule = LiturgySchedule::with(['assignments.personnel', 'assignments.lingkungan'])->findOrFail($id);
        $userRole = Auth::user()->role;

        // 2. Inisialisasi Koleksi Kosong (Agar tidak error undefined variable)
        $misdinars = collect(); 
        $lektors   = collect();
        $mazmurs   = collect();
        $organis   = collect();
        
        // 3. Ambil Data Wilayah & Lingkungan (Untuk Padus & Parkir)
        // Kita gunakan get() agar semua wilayah termuat
        $territories = \App\Models\Territory::with(['lingkungans' => function($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        // 4. LOGIKA PENGAMBILAN DATA PETUGAS (WAJIB PAKAI get())
        
        // KASUS A: ADMIN & PENGURUS (Bisa akses semua data)
        if (in_array($userRole, ['admin', 'pengurus_gereja'])) {
            $misdinars = LiturgyPersonnel::where('type', 'Misdinar')->orderBy('name', 'asc')->get();
            $lektors   = LiturgyPersonnel::where('type', 'Lektor')->orderBy('name', 'asc')->get();
            $mazmurs   = LiturgyPersonnel::where('type', 'Mazmur')->orderBy('name', 'asc')->get();
            $organis   = LiturgyPersonnel::where('type', 'Organis')->orderBy('name', 'asc')->get();
            
            $roles = ['Misdinar', 'Lektor', 'Mazmur', 'Organis', 'Paduan Suara', 'Parkir'];
        } 
        
        // KASUS B: DIREKTUR MUSIK (Hanya Musik)
        elseif ($userRole == 'direktur_musik') {
            $mazmurs   = LiturgyPersonnel::where('type', 'Mazmur')->orderBy('name', 'asc')->get();
            $organis   = LiturgyPersonnel::where('type', 'Organis')->orderBy('name', 'asc')->get();
            
            $roles = ['Mazmur', 'Organis', 'Paduan Suara'];
        }
        
        // KASUS C: MISDINAR (Hanya Misdinar)
        elseif ($userRole == 'misdinar') {
            // Pastikan pakai get() agar SEMUA data misdinar muncul
            $misdinars = LiturgyPersonnel::where('type', 'Misdinar')->orderBy('name', 'asc')->get();
            
            // Debugging (Opsional: Hapus tanda // di bawah jika ingin cek jumlah data di layar putih)
            // dd($misdinars->count(), $misdinars->pluck('name')); 

            $roles = ['Misdinar'];
        }
        
        // KASUS D: LEKTOR (Hanya Lektor)
        elseif ($userRole == 'lektor') {
            $lektors = LiturgyPersonnel::where('type', 'Lektor')->orderBy('name', 'asc')->get();
            $roles = ['Lektor'];
        } 
        
        else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // 5. Kirim ke View
        return view('admin.liturgy.assign', compact(
            'schedule', 'roles', 'territories',
            'misdinars', 'lektors', 'mazmurs', 'organis'
        ));
    }

    public function assignmentStore(Request $request, $scheduleId)
    {
        $role = $request->role;
        $personnelId = $request->input('personnel_id');
        $lingkunganId = $request->input('lingkungan_id');
        $description = null; // Inisialisasi deskripsi

        // =========================================================================
        // LOGIKA BARU: MENANGANI INPUT MANUAL UNTUK PETUGAS LUAR
        // =========================================================================
        if ($request->boolean('is_external_manual')) {
            
            // 1. Validasi input teks yang diketik manual
            $request->validate(['description' => 'required|string|max:255']);

            // 2. Pastikan fitur ini hanya untuk Paduan Suara & Parkir
            if (!in_array($role, ['Paduan Suara', 'Parkir'])) {
                return back()->with('error', 'Fitur petugas luar hanya untuk Paduan Suara dan Parkir.');
            }

            // 3. Siapkan data untuk disimpan: Kosongkan ID, isi deskripsi
            $description = $request->description;
            $personnelId = null;
            $lingkunganId = null;
            
        } 
        // =========================================================================
        // LOGIKA LAMA (YANG SUDAH ADA): UNTUK PETUGAS INTERNAL
        // =========================================================================
        elseif (in_array($role, ['Paduan Suara', 'Parkir'])) {
            // Jika tugas kelompok internal, validasi lingkungan_id
            $request->validate(['lingkungan_id' => 'required|exists:lingkungans,id']);
        } 
        else {
            // Jika tugas perorangan, validasi personnel_id
            $request->validate(['personnel_id' => 'required|exists:liturgy_personnels,id']);
        }

        // --- CEK DUPLIKASI DATA (GABUNGAN) ---
        $isDuplicate = \App\Models\LiturgyAssignment::where('liturgy_schedule_id', $scheduleId)
            ->where('role', $role)
            ->where(function ($query) use ($personnelId, $lingkunganId, $description) {
                if ($description) {
                    // Cek duplikasi berdasarkan teks deskripsi untuk petugas luar
                    $query->where('description', $description);
                } elseif ($lingkunganId) {
                    // Cek duplikasi berdasarkan ID lingkungan
                    $query->where('lingkungan_id', $lingkunganId);
                } elseif ($personnelId) {
                    // Cek duplikasi berdasarkan ID personel
                    $query->where('liturgy_personnel_id', $personnelId);
                }
            })->exists();

        if ($isDuplicate) {
            return back()->with('error', 'Gagal: Petugas atau kelompok tersebut sudah terdaftar di jadwal ini.');
        }
        
        // --- SIMPAN KE DATABASE ---
        \App\Models\LiturgyAssignment::create([
            'liturgy_schedule_id' => $scheduleId,
            'role' => $role,
            'liturgy_personnel_id' => $personnelId,
            'lingkungan_id' => $lingkunganId,
            'description' => $description // Kolom ini akan terisi jika petugas dari luar
        ]);

        return back()->with('success', 'Petugas berhasil ditambahkan ke jadwal.');
    }

    public function assignmentDestroy($id)
    {
        $assignment = LiturgyAssignment::findOrFail($id);
        $userRole = Auth::user()->role;

        // --- SECURITY CHECK (Hapus Tugas) ---
        if ($userRole == 'misdinar' && $assignment->role !== 'Misdinar') {
            return back()->with('error', 'Anda tidak berhak menghapus data selain Misdinar!');
        }
        if ($userRole == 'lektor' && $assignment->role !== 'Lektor') {
            return back()->with('error', 'Anda tidak berhak menghapus data selain Lektor!');
        }
        if ($userRole == 'direktur_musik' && !in_array($assignment->role, ['Mazmur', 'Organis', 'Paduan Suara'])) {
            return back()->with('error', 'Anda hanya boleh menghapus data musik!');
        }

        $assignment->delete();
        return back()->with('success', 'Petugas dihapus dari jadwal.');
    }
}