<?php

namespace App\Http\Controllers\Admin;

use App\Models\Territory;
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
        $schedules = LiturgySchedule::orderBy('event_at', 'desc')->paginate(10);
        return view('admin.liturgy.schedules', compact('schedules'));
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

    // =========================================================================
    // BAGIAN 3: PENUGASAN (ASSIGNMENT) - INTI SISTEM
    // =========================================================================

    // Pastikan import model ini ada di paling atas file
    // use App\Models\Territory; 
    // use App\Models\LiturgyPersonnel;
    // use App\Models\LiturgySchedule;
    // use App\Models\Lingkungan;

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
        $personnelId = $request->personnel_id;
        $lingkunganId = $request->lingkungan_id;

        // --- 1. CEK: APAKAH INPUT PETUGAS BARU (EKSTERNAL)? ---
        if ($request->boolean('is_new_external')) {
            
            // [VALIDASI TAMBAHAN]
            // Pastikan fitur Eksternal HANYA untuk Paduan Suara & Parkir
            if (!in_array($role, ['Paduan Suara', 'Parkir'])) {
                return back()->with('error', 'Gagal: Fitur petugas luar hanya berlaku untuk Paduan Suara dan Parkir.');
            }

            $request->validate([
                'new_name' => 'required|string|max:255',
                'new_description' => 'required|string|max:255'
            ]);

            // Buat data personnel baru
            $newPerson = LiturgyPersonnel::create([
                'name' => $request->new_name,
                'type' => $role,
                'is_external' => true,
                'external_description' => $request->new_description,
                'lingkungan_id' => null
            ]);

            $personnelId = $newPerson->id;
            $lingkunganId = null;
        }

        // --- 2. VALIDASI & SIMPAN KE JADWAL ---

        // KASUS A: TUGAS KELOMPOK INTERNAL (Padus/Parkir dr Lingkungan)
        if (in_array($role, ['Paduan Suara', 'Parkir']) && !$personnelId) {
            $request->validate(['lingkungan_id' => 'required']);
            
            // Cek Double (Lingkungan sama di tugas sama)
            $exists = LiturgyAssignment::where('liturgy_schedule_id', $scheduleId)
                        ->where('lingkungan_id', $lingkunganId)
                        ->whereIn('role', ['Paduan Suara', 'Parkir'])
                        ->exists();
            
            if($exists) return back()->with('error', 'Gagal: Lingkungan ini sudah bertugas di jadwal ini.');
        }
        
        // KASUS B: TUGAS PERORANGAN / EKSTERNAL (Misdinar, Lektor, atau Padus Luar)
        else {
            // Jika bukan input baru, pastikan personnel_id terpilih
            if (!$request->boolean('is_new_external')) {
                $request->validate(['personnel_id' => 'required']);
            }

            // Cek Nama Kembar (Validasi Manual agar akurat)
            $calon = LiturgyPersonnel::find($personnelId);
            $namaCalon = trim(strtolower($calon->name));
            
            $currentAssignments = LiturgyAssignment::where('liturgy_schedule_id', $scheduleId)
                                ->whereNotNull('liturgy_personnel_id')
                                ->with('personnel')->get();
                                
            foreach($currentAssignments as $asg) {
                if($asg->personnel && trim(strtolower($asg->personnel->name)) === $namaCalon) {
                    return back()->with('error', "Gagal: {$calon->name} sudah terdaftar di jadwal ini.");
                }
            }
        }

        // Simpan Assignment
        LiturgyAssignment::create([
            'liturgy_schedule_id' => $scheduleId,
            'role' => $role,
            'liturgy_personnel_id' => $personnelId,
            'lingkungan_id' => $lingkunganId
        ]);

        return back()->with('success', 'Petugas berhasil ditambahkan.');
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