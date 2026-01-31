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
        // 1. Ambil Data Wilayah
        $wilayahs = Territory::orderBy('name')->get();
        
        // 2. Ambil Data Lingkungan
        $lingkungans = Lingkungan::with('territory')->orderBy('name')->get();

        // BAGIAN ORGANISASI DIHAPUS agar tidak muncul di dropdown
        
        return view('admin.facility.create', compact('wilayahs', 'lingkungans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required',
            'booked_by' => 'required_without:booked_by_text', 
            'purpose' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $data = $request->all();

        // Jika diketik manual (misal: OMK), pakai input text
        if ($request->filled('booked_by_text')) {
            $data['booked_by'] = $request->booked_by_text;
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
            'end_time' => 'required|date|after:start_time',
        ]);

        $data = $request->all();

        if ($request->filled('booked_by_text')) {
            $data['booked_by'] = $request->booked_by_text;
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