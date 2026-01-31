<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityBooking;
use Illuminate\Http\Request;

class FacilityBookingController extends Controller
{
    public function index()
    {
        // Urutkan dari yang terbaru (descending)
        $bookings = FacilityBooking::orderBy('start_time', 'desc')->paginate(10);
        return view('admin.facility.index', compact('bookings'));
    }

    public function create()
    {
        return view('admin.facility.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required',
            'booked_by' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time', // Selesai harus setelah Mulai
        ]);

        FacilityBooking::create($request->all());

        return redirect()->route('admin.facility-bookings.index')->with('success', 'Jadwal pemakaian gedung berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $booking = FacilityBooking::findOrFail($id);
        return view('admin.facility.edit', compact('booking'));
    }

    /**
     * Menyimpan perubahan.
     */
    public function update(Request $request, $id)
    {
        $booking = FacilityBooking::findOrFail($id);

        $request->validate([
            'facility_name' => 'required',
            'booked_by' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $booking->update($request->all());

        return redirect()->route('admin.facility-bookings.index')
                         ->with('success', 'Jadwal berhasil diperbarui.');
    }
    public function destroy($id)
    {
        FacilityBooking::destroy($id);
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}