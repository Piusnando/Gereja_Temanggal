<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LiturgyPersonnel;
use App\Models\LiturgySchedule;
use App\Models\LiturgyAssignment;
use App\Models\Lingkungan; // Atau Community

class LiturgyController extends Controller
{
    // --- BAGIAN 1: DATABASE PETUGAS (PERSONNEL) ---
    
    public function personnelIndex(Request $request)
    {
        $type = $request->query('type'); // Ambil dari URL ?type=Misdinar

        $query = LiturgyPersonnel::with('lingkungan');

        if ($type) {
            $query->where('type', $type);
        }

        $personnels = $query->latest()->paginate(15);
        
        // Kirim data type ke view agar judul halaman dinamis
        return view('admin.liturgy.personnels', compact('personnels', 'type'));
    }

    public function personnelCreate(Request $request)
    {
        $type = $request->query('type'); // Agar otomatis terpilih saat input
        $lingkungans = Lingkungan::all();
        return view('admin.liturgy.personnels_create', compact('lingkungans', 'type'));
    }

    public function personnelStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required', // Wajib ada tipe
            'lingkungan_id' => 'required_without:is_external',
        ]);

        LiturgyPersonnel::create([
            'name' => $request->name,
            'type' => $request->type, // Simpan tipenya
            'lingkungan_id' => $request->lingkungan_id,
            'is_external' => $request->has('is_external'),
            'external_description' => $request->external_description
        ]);

        // Redirect kembali ke halaman list sesuai tipenya
        return redirect()->route('admin.liturgy.personnels', ['type' => $request->type])
                         ->with('success', 'Data ' . $request->type . ' berhasil disimpan');
    }

    // --- BAGIAN 2: JADWAL & PENUGASAN ---

    public function scheduleIndex()
    {
        // Tampilkan jadwal yang akan datang
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
        return redirect()->route('admin.liturgy.schedules')->with('success', 'Jadwal Misa dibuat');
    }

    public function scheduleEdit($id)
    {
        $schedule = LiturgySchedule::with(['assignments.personnel', 'assignments.lingkungan'])->findOrFail($id);
        
        // AMBIL DATA TERPISAH
        $misdinars = LiturgyPersonnel::where('type', 'Misdinar')->orderBy('name')->get();
        $lektors   = LiturgyPersonnel::where('type', 'Lektor')->orderBy('name')->get();
        $mazmurs   = LiturgyPersonnel::where('type', 'Mazmur')->orderBy('name')->get();
        $organis   = LiturgyPersonnel::where('type', 'Organis')->orderBy('name')->get();
        
        $lingkungans = Lingkungan::orderBy('name')->get();
        $roles = ['Misdinar', 'Lektor', 'Mazmur', 'Organis', 'Paduan Suara', 'Parkir'];

        return view('admin.liturgy.assign', compact(
            'schedule', 'roles', 'lingkungans',
            'misdinars', 'lektors', 'mazmurs', 'organis'
        ));
    }

    public function assignmentStore(Request $request, $scheduleId)
    {
        // Validasi Dinamis
        $role = $request->role;
        
        if (in_array($role, ['Paduan Suara', 'Parkir'])) {
            $request->validate(['lingkungan_id' => 'required', 'role' => 'required']);
            $data = [
                'liturgy_schedule_id' => $scheduleId,
                'role' => $role,
                'lingkungan_id' => $request->lingkungan_id,
                'liturgy_personnel_id' => null
            ];
            // Cek Double (Lingkungan sama di tugas sama)
            $isDouble = LiturgyAssignment::where('liturgy_schedule_id', $scheduleId)
                        ->where('lingkungan_id', $request->lingkungan_id)
                        ->where('role', $role)
                        ->exists();
        } else {
            // Logika Petugas Perorangan (Misdinar, Lektor, dll)
            $request->validate(['personnel_id' => 'required', 'role' => 'required']);
            $data = [
                'liturgy_schedule_id' => $scheduleId,
                'role' => $role,
                'liturgy_personnel_id' => $request->personnel_id,
                'lingkungan_id' => null
            ];
            
            // Cek Tabrakan Jadwal (Orang yang sama di jam yang sama)
            $schedule = LiturgySchedule::findOrFail($scheduleId);
            $isConflict = LiturgyAssignment::where('liturgy_personnel_id', $request->personnel_id)
                ->whereHas('schedule', function($q) use ($schedule) {
                    $q->whereDate('event_at', $schedule->event_at->format('Y-m-d'))
                      ->where('id', '!=', $schedule->id);
                })->exists();
            
            if ($isConflict) return back()->with('error', 'GAGAL! Petugas sudah bertugas di jadwal lain hari ini.');

            // Cek Double di jadwal ini
            $isDouble = LiturgyAssignment::where('liturgy_schedule_id', $scheduleId)
                        ->where('liturgy_personnel_id', $request->personnel_id)->exists();
        }

        if($isDouble) return back()->with('error', 'Data petugas/lingkungan ini sudah masuk di jadwal ini.');

        LiturgyAssignment::create($data);

        return back()->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function assignmentDestroy($id)
    {
        LiturgyAssignment::destroy($id);
        return back()->with('success', 'Petugas dihapus dari jadwal.');
    }
}