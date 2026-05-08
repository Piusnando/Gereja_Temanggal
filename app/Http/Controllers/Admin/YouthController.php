<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YouthMember;
use App\Models\YouthEvent;
use App\Models\YouthAttendance;
use App\Models\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YouthController extends Controller
{
    // 1. Menerjemahkan URL (omk / pia-pir) ke Format Database (OMK / PIA/PIR)
    private function resolveCategory($categoryUrl) {
        return strtolower($categoryUrl) === 'pia-pir' ? 'PIA/PIR' : 'OMK';
    }

    // 2. Keamanan: Cek apakah user berhak mengakses URL ini
    private function checkAccess($categoryUrl) {
        $role = Auth::user()->role;
        // Admin & Pengurus bebas akses keduanya
        if (in_array($role, ['admin', 'pengurus_gereja'])) return true;
        // Jika login OMK, hanya boleh akses URL 'omk'
        if (strtolower($categoryUrl) === 'omk' && $role === 'omk') return true;
        // Jika login PIA/PIR, hanya boleh akses URL 'pia-pir'
        if (strtolower($categoryUrl) === 'pia-pir' && $role === 'pia_pir') return true;
        
        abort(403, 'Akses Ditolak. Anda tidak memiliki hak akses ke halaman ini.');
    }

    // ==========================================
    // MANAJEMEN ANGGOTA
    // ==========================================
    public function membersIndex($categoryUrl)
    {
        $this->checkAccess($categoryUrl);
        $dbCategory = $this->resolveCategory($categoryUrl);

        $members = YouthMember::with(['territory', 'lingkungan'])
                    ->where('category', $dbCategory)->orderBy('name')->paginate(15);
        $territories = Territory::with('lingkungans')->orderBy('name')->get();

        return view('admin.youth.members', compact('members', 'dbCategory', 'categoryUrl', 'territories'));
    }

    public function memberStore(Request $request, $categoryUrl)
    {
        $this->checkAccess($categoryUrl);
        $dbCategory = $this->resolveCategory($categoryUrl);

        $data = $request->validate([
            'name' => 'required|string',
            'baptism_name' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'territory_id' => 'nullable|exists:territories,id',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);
        
        $data['category'] = $dbCategory;
        YouthMember::create($data);
        
        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function memberDestroy($categoryUrl, $id)
    {
        $this->checkAccess($categoryUrl);
        YouthMember::findOrFail($id)->delete();
        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    // ==========================================
    // MANAJEMEN KEGIATAN & ABSENSI
    // ==========================================
    public function eventsIndex($categoryUrl)
    {
        $this->checkAccess($categoryUrl);
        $dbCategory = $this->resolveCategory($categoryUrl);

        // 1. Ambil daftar kegiatan beserta jumlah yang HADIR (untuk ditampilkan di card)
        $events = YouthEvent::withCount(['attendances' => function($query) {
                        $query->where('status', 'Hadir');
                    }])
                    ->where('category', $dbCategory)
                    ->orderBy('event_date', 'desc')
                    ->paginate(6);
        
        // 2. Siapkan Data untuk Grafik (Ambil 10 Kegiatan Terakhir)
        $latestEvents = YouthEvent::where('category', $dbCategory)
                        ->withCount(['attendances' => function($query) {
                            $query->where('status', 'Hadir');
                        }])
                        ->orderBy('event_date', 'desc')
                        ->take(10)
                        ->get()
                        ->reverse() // Balik urutan agar di grafik tampil dari kiri (lama) ke kanan (baru)
                        ->values();

        // 3. Pisahkan nama label (tanggal + judul) dan nilai angkanya
        $chartLabels = $latestEvents->map(function($e) {
            return $e->event_date->format('d M') . ' (' . \Illuminate\Support\Str::limit($e->title, 12) . ')';
        })->toArray();

        $chartData = $latestEvents->pluck('attendances_count')->toArray();

        return view('admin.youth.events', compact('events', 'dbCategory', 'categoryUrl', 'chartLabels', 'chartData'));
    }
    public function eventStore(Request $request, $categoryUrl)
    {
        $this->checkAccess($categoryUrl);
        $dbCategory = $this->resolveCategory($categoryUrl);

        $request->validate(['title' => 'required', 'event_date' => 'required|date']);
        
        YouthEvent::create([
            'category' => $dbCategory,
            'title' => $request->title,
            'event_date' => $request->event_date,
            'description' => $request->description,
        ]);
        
        return back()->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function attendanceShow($categoryUrl, $id)
    {
        $this->checkAccess($categoryUrl);
        $dbCategory = $this->resolveCategory($categoryUrl);

        $event = YouthEvent::findOrFail($id);
        if ($event->category !== $dbCategory) abort(404); // Keamanan tambahan

        $members = YouthMember::where('category', $dbCategory)
            ->with(['attendances' => function($q) use ($event) {
                $q->where('youth_event_id', $event->id);
            }])->orderBy('name')->get();

        return view('admin.youth.attendance', compact('event', 'members', 'categoryUrl'));
    }

    public function attendanceStore(Request $request, $categoryUrl, $id)
    {
        $this->checkAccess($categoryUrl);
        $event = YouthEvent::findOrFail($id);

        if($request->has('attendances')){
            foreach ($request->attendances as $memberId => $status) {
                YouthAttendance::updateOrCreate(['youth_event_id' => $event->id, 'youth_member_id' => $memberId],['status' => $status]
                );
            }
        }

        return back()->with('success', 'Absensi berhasil disimpan!');
    }

    public function memberEdit($categoryUrl, $id)
    {
        $this->checkAccess($categoryUrl);
        $dbCategory = $this->resolveCategory($categoryUrl);
        
        $member = YouthMember::findOrFail($id);
        
        // Keamanan: Pastikan data yang diedit sesuai dengan kategorinya
        if ($member->category !== $dbCategory) {
            abort(404);
        }

        $territories = Territory::with('lingkungans')->orderBy('name')->get();

        return view('admin.youth.members_edit', compact('member', 'dbCategory', 'categoryUrl', 'territories'));
    }

    public function memberUpdate(Request $request, $categoryUrl, $id)
    {
        $this->checkAccess($categoryUrl);
        $dbCategory = $this->resolveCategory($categoryUrl);

        $member = YouthMember::findOrFail($id);

        if ($member->category !== $dbCategory) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string',
            'baptism_name' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'territory_id' => 'nullable|exists:territories,id',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);
        
        $member->update($data);
        
        return redirect()->route('admin.youth.members', $categoryUrl)->with('success', 'Data anggota berhasil diperbarui.');
    }
}