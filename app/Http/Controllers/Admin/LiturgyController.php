<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Facade untuk Auth
use App\Models\LiturgyPersonnel;
use App\Models\LiturgySchedule;
use App\Models\LiturgyAssignment;
use App\Models\Lingkungan;

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

    // =========================================================================
    // BAGIAN 3: PENUGASAN (ASSIGNMENT) - INTI SISTEM
    // =========================================================================

    public function scheduleEdit($id)
    {
        $userRole = Auth::user()->role;

        // A. FILTER DAFTAR TUGAS DI TABEL KANAN (Eager Loading Constraint)
        // Agar Misdinar hanya melihat list Misdinar, dst.
        $assignmentFilter = function($query) use ($userRole) {
            if ($userRole == 'misdinar') {
                $query->where('role', 'Misdinar');
            } 
            elseif ($userRole == 'lektor') {
                $query->where('role', 'Lektor');
            } 
            elseif ($userRole == 'direktur_musik') {
                $query->whereIn('role', ['Mazmur', 'Organis', 'Paduan Suara']);
            }
            // Admin & Pengurus mengambil semua (tidak masuk if)
        };

        $schedule = LiturgySchedule::with([
            'assignments' => $assignmentFilter, 
            'assignments.personnel', 
            'assignments.lingkungan'
        ])->findOrFail($id);
        
        // B. FILTER DROPDOWN INPUT DI KIRI (Agar pilihan sesuai role)
        $misdinars = collect(); 
        $lektors   = collect();
        $mazmurs   = collect();
        $organis   = collect();
        $lingkungans = collect(); 
        $roles = [];

        if (in_array($userRole, ['admin', 'pengurus_gereja'])) {
            $misdinars = LiturgyPersonnel::where('type', 'Misdinar')->orderBy('name')->get();
            $lektors   = LiturgyPersonnel::where('type', 'Lektor')->orderBy('name')->get();
            $mazmurs   = LiturgyPersonnel::where('type', 'Mazmur')->orderBy('name')->get();
            $organis   = LiturgyPersonnel::where('type', 'Organis')->orderBy('name')->get();
            $lingkungans = Lingkungan::orderBy('name')->get();
            $roles = ['Misdinar', 'Lektor', 'Mazmur', 'Organis', 'Paduan Suara', 'Parkir'];
        } 
        elseif ($userRole == 'direktur_musik') {
            $mazmurs   = LiturgyPersonnel::where('type', 'Mazmur')->orderBy('name')->get();
            $organis   = LiturgyPersonnel::where('type', 'Organis')->orderBy('name')->get();
            $lingkungans = Lingkungan::orderBy('name')->get(); 
            $roles = ['Mazmur', 'Organis', 'Paduan Suara'];
        }
        elseif ($userRole == 'misdinar') {
            $misdinars = LiturgyPersonnel::where('type', 'Misdinar')->orderBy('name')->get();
            $roles = ['Misdinar']; 
        }
        elseif ($userRole == 'lektor') {
            $lektors = LiturgyPersonnel::where('type', 'Lektor')->orderBy('name')->get();
            $roles = ['Lektor']; 
        } 
        else {
            abort(403, 'Role Anda tidak memiliki akses pengaturan jadwal.');
        }

        return view('admin.liturgy.assign', compact(
            'schedule', 'roles', 'lingkungans',
            'misdinars', 'lektors', 'mazmurs', 'organis'
        ));
    }

    public function assignmentStore(Request $request, $scheduleId)
    {
        $userRole = Auth::user()->role;
        $roleInput = $request->role;

        // --- 1. SECURITY CHECK (Validasi Hak Akses Role) ---
        if ($userRole == 'misdinar' && $roleInput !== 'Misdinar') {
            return back()->with('error', 'Akses Ditolak: Akun Misdinar hanya boleh mengatur petugas Misdinar!');
        }
        if ($userRole == 'lektor' && $roleInput !== 'Lektor') {
            return back()->with('error', 'Akses Ditolak: Akun Lektor hanya boleh mengatur petugas Lektor!');
        }
        if ($userRole == 'direktur_musik' && !in_array($roleInput, ['Mazmur', 'Organis', 'Paduan Suara'])) {
            return back()->with('error', 'Akses Ditolak: Direktur Musik hanya boleh mengatur petugas musik!');
        }

        // =========================================================
        // [BARU] CEK BATASAN JUMLAH PETUGAS (MAX 1)
        // =========================================================
        // Role yang hanya boleh diisi 1 orang/kelompok
        $singleRoles = ['Paduan Suara', 'Parkir', 'Mazmur', 'Organis'];

        if (in_array($roleInput, $singleRoles)) {
            // Cek apakah di jadwal ini role tersebut sudah ada isinya?
            $isFilled = LiturgyAssignment::where('liturgy_schedule_id', $scheduleId)
                        ->where('role', $roleInput)
                        ->exists();

            if ($isFilled) {
                return back()->with('error', "GAGAL: Posisi $roleInput sudah terisi. Harap hapus petugas lama terlebih dahulu jika ingin menggantinya.");
            }
        }
        // =========================================================

        
        // --- 2. LOGIKA TUGAS KELOMPOK (Paduan Suara & Parkir) ---
        if (in_array($roleInput, ['Paduan Suara', 'Parkir'])) {
            
            // JIKA DARI LUAR (EXTERNAL)
            if ($request->has('is_external_group')) {
                $request->validate(['external_name' => 'required|string|max:255']);
                
                LiturgyAssignment::create([
                    'liturgy_schedule_id' => $scheduleId,
                    'role' => $roleInput,
                    'lingkungan_id' => null,
                    'liturgy_personnel_id' => null,
                    'description' => $request->external_name
                ]);
            } 
            // JIKA DARI DALAM (INTERNAL)
            else {
                $request->validate(['lingkungan_id' => 'required']);
                // (Validasi double check dihapus disini karena sudah dicover oleh logic "Max 1" diatas)

                LiturgyAssignment::create([
                    'liturgy_schedule_id' => $scheduleId,
                    'role' => $roleInput,
                    'lingkungan_id' => $request->lingkungan_id,
                    'liturgy_personnel_id' => null,
                    'description' => null
                ]);
            }
        }

        // --- 3. LOGIKA TUGAS PERORANGAN (Misdinar, Lektor, dll) ---
        else {
            $request->validate(['personnel_id' => 'required']);

            $calonPetugas = LiturgyPersonnel::findOrFail($request->personnel_id);
            $namaCalon = trim(strtolower($calonPetugas->name));

            $petugasTerjadwal = LiturgyAssignment::where('liturgy_schedule_id', $scheduleId)
                                ->whereNotNull('liturgy_personnel_id')
                                ->with('personnel')
                                ->get();

            foreach ($petugasTerjadwal as $tugas) {
                if ($tugas->personnel) {
                    $namaAda = trim(strtolower($tugas->personnel->name));
                    if ($namaCalon === $namaAda) {
                        return back()->with('error', "GAGAL: Sdr/i {$calonPetugas->name} sudah terdaftar sebagai {$tugas->role}. Tidak boleh rangkap tugas!");
                    }
                }
            }

            LiturgyAssignment::create([
                'liturgy_schedule_id' => $scheduleId,
                'role' => $roleInput,
                'liturgy_personnel_id' => $request->personnel_id,
                'lingkungan_id' => null
            ]);
        }

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