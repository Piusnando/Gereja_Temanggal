<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lingkungan;
use App\Models\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LingkunganController extends Controller
{
    public function index()
    {
        // Urutkan berdasarkan Wilayah dulu, baru Nama Lingkungan
        $lingkungans = Lingkungan::with('territory')
                        ->join('territories', 'lingkungans.territory_id', '=', 'territories.id')
                        ->orderBy('territories.name')
                        ->orderBy('lingkungans.name')
                        ->select('lingkungans.*') // Hindari konflik id columns
                        ->paginate(10);

        return view('admin.lingkungan.index', compact('lingkungans'));
    }

    public function create()
    {
        $territories = Territory::orderBy('name')->get();
        return view('admin.lingkungan.create', compact('territories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'territory_id' => 'required|exists:territories,id',
            'patron_saint' => 'nullable|string',
            'saint_image' => 'nullable|image|max:2048', // Max 2MB
            'chief_name' => 'nullable|string',
            'info' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('saint_image')) {
            $data['saint_image'] = $request->file('saint_image')->store('uploads/saints', 'public');
        }

        Lingkungan::create($data);

        return redirect()->route('admin.lingkungan.index')->with('success', 'Lingkungan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lingkungan = Lingkungan::findOrFail($id);
        $territories = Territory::orderBy('name')->get();
        return view('admin.lingkungan.edit', compact('lingkungan', 'territories'));
    }

    public function update(Request $request, $id)
    {
        $lingkungan = Lingkungan::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'territory_id' => 'required|exists:territories,id',
            'patron_saint' => 'nullable|string',
            'saint_image' => 'nullable|image|max:2048',
            'chief_name' => 'nullable|string',
            'info' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('saint_image')) {
            // Hapus foto lama jika ada
            if ($lingkungan->saint_image && Storage::disk('public')->exists($lingkungan->saint_image)) {
                Storage::disk('public')->delete($lingkungan->saint_image);
            }
            $data['saint_image'] = $request->file('saint_image')->store('uploads/saints', 'public');
        }

        $lingkungan->update($data);

        return redirect()->route('admin.lingkungan.index')->with('success', 'Data Lingkungan diperbarui.');
    }

    public function destroy($id)
    {
        $lingkungan = Lingkungan::findOrFail($id);
        
        if ($lingkungan->saint_image && Storage::disk('public')->exists($lingkungan->saint_image)) {
            Storage::disk('public')->delete($lingkungan->saint_image);
        }
        
        $lingkungan->delete();

        return back()->with('success', 'Lingkungan dihapus.');
    }
}