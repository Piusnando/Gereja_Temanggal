<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvLocation;
use Illuminate\Http\Request;

class InvLocationController extends Controller
{
    public function index()
    {
        $locations = InvLocation::latest()->paginate(10);
        return view('admin.inventaris.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.inventaris.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Wajib 4 karakter, huruf saja, dan harus unik
            'code' => 'required|alpha|size:4|unique:inv_locations,code', 
        ],[
            'code.size' => 'Kode lokasi HARUS persis 4 huruf.',
            'code.unique' => 'Kode lokasi ini sudah dipakai, silakan cari kombinasi lain.'
        ]);

        InvLocation::create([
            'name' => $request->name,
            'code' => strtoupper($request->code) // Paksa jadi huruf besar semua
        ]);

        return redirect()->route('admin.inventaris.locations.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(InvLocation $location)
    {
        return view('admin.inventaris.locations.edit', compact('location'));
    }

    public function update(Request $request, InvLocation $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Abaikan aturan unik jika kodenya tidak berubah
            'code' => 'required|alpha|size:4|unique:inv_locations,code,' . $location->id,
        ]);

        $location->update([
            'name' => $request->name,
            'code' => strtoupper($request->code)
        ]);

        return redirect()->route('admin.inventaris.locations.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(InvLocation $location)
    {
        // Cegah penghapusan jika lokasi ini sudah dipakai oleh barang
        if ($location->items()->count() > 0) {
            return back()->with('error', 'Gagal: Lokasi ini sedang digunakan oleh data barang. Hapus atau pindahkan barangnya terlebih dahulu.');
        }

        $location->delete();
        return back()->with('success', 'Lokasi berhasil dihapus.');
    }
}