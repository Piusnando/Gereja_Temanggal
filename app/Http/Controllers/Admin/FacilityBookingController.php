<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityBooking;
use App\Models\Territory; 
use App\Models\Lingkungan; 
// use App\Models\OrganizationMember; // HAPUS INI (Tidak lagi dipakai)
use Illuminate\Http\Request;

class FacilityBookingController extends Controller
{
    public function index()
    {
        $bookings = FacilityBooking::orderBy('start_time', 'desc')->paginate(10);
        return view('admin.facility.index', compact('bookings'));
    }

    public function create()
    {
        $wilayahs = Territory::orderBy('name')->get();
        $lingkungans = Lingkungan::with('territory')->orderBy('name')->get();
        return view('admin.facility.create', compact('wilayahs', 'lingkungans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required',
            'booked_by' => 'required_without:booked_by_text', 
            'purpose' => 'required|string|max:255',
            'start_time' => 'required|date',
            // --- PERUBAHAN DI SINI ---
            // 'required' diubah menjadi 'nullable'
            // 'after' diubah menjadi 'after_or_equal' agar bisa sama (untuk acara singkat)
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $data = $request->only(['facility_name', 'purpose', 'start_time', 'end_time']);

        if ($request->filled('booked_by_text')) {
            $data['booked_by'] = $request->booked_by_text;
        } else {
            $data['booked_by'] = $request->booked_by;
        }
        
        // Tambahan: Jika end_time tidak diisi, set nilainya jadi null
        if (!$request->filled('end_time')) {
            $data['end_time'] = null;
        }

        FacilityBooking::create($data);

        return redirect()->route('admin.facility-bookings.index')->with('success', 'Jadwal pemakaian gedung berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $booking = FacilityBooking::findOrFail($id);
        
        $wilayahs = Territory::orderBy('name')->get();
        $lingkungans = Lingkungan::orderBy('name')->get();
        
        // BAGIAN ORGANISASI DIHAPUS

        return view('admin.facility.edit', compact('booking', 'wilayahs', 'lingkungans'));
    }

    public function update(Request $request, $id)
    {
        $booking = FacilityBooking::findOrFail($id);

        $request->validate([
            'facility_name' => 'required',
            'purpose' => 'required|string|max:255',
            'start_time' => 'required|date',
            // --- PERUBAHAN DI SINI JUGA ---
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $data = $request->only(['facility_name', 'purpose', 'start_time', 'end_time']);

        if ($request->filled('booked_by_text')) {
            $data['booked_by'] = $request->booked_by_text;
        } else {
            $data['booked_by'] = $request->booked_by;
        }
        
        // Tambahan: Jika end_time dikosongkan saat edit, set jadi null
        if (!$request->filled('end_time')) {
            $data['end_time'] = null;
        }

        $booking->update($data);

        return redirect()->route('admin.facility-bookings.index')
                         ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        FacilityBooking::destroy($id);
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}