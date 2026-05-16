<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lingkungan;
use App\Models\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- Pastikan ini ada

class LingkunganController extends Controller
{
    use AuthorizesRequests; // <-- Pastikan ini ada untuk mengaktifkan $this->authorize()

    public function index()
    {
        $user = Auth::user();
        $query = Lingkungan::with('territory');
        
        // Filter Data Berdasarkan Role (DIPERBAIKI: Tambah prefix 'lingkungans.')
        if ($user->role === 'ketua_wilayah') {
            $query->where('lingkungans.territory_id', $user->territory_id);
        } elseif ($user->role === 'ketua_lingkungan') {
            $query->where('lingkungans.id', $user->lingkungan_id); // <-- Ini yang menyebabkan error tadi
        }

        // Urutkan dan Paginasi
        $lingkungans = $query->join('territories', 'lingkungans.territory_id', '=', 'territories.id')
                        ->orderBy('territories.name')
                        ->orderBy('lingkungans.name')
                        ->select('lingkungans.*')
                        ->paginate(10);

        return view('admin.lingkungan.index', compact('lingkungans'));
    }


    public function create()
    {
        $this->authorize('create', Lingkungan::class); // Satpam untuk create
        $territories = Territory::orderBy('name')->get();
        return view('admin.lingkungan.create', compact('territories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Lingkungan::class); // Satpam untuk create
        $request->validate([
            'name' => 'required',
            'territory_id' => 'required|exists:territories,id',
            'patron_saint' => 'nullable|string',
            'saint_image' => 'nullable|image|max:2048',
            'chief_name' => 'nullable|string',
            'info' => 'nullable|string',
        ]);

        $data = $request->except('saint_image');

        if ($request->hasFile('saint_image')) {
            $data['saint_image'] = $request->file('saint_image')->store('uploads/saints', 'public');
        }

        Lingkungan::create($data);

        return redirect()->route('admin.lingkungan.index')->with('success', 'Lingkungan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lingkungan = Lingkungan::findOrFail($id);
        $this->authorize('update', $lingkungan); // Panggil "Satpam" Policy

        $territories = Territory::orderBy('name')->get();
        return view('admin.lingkungan.edit', compact('lingkungan', 'territories'));
    }

    public function update(Request $request, $id)
    {
        $lingkungan = Lingkungan::findOrFail($id);
        $this->authorize('update', $lingkungan); // Panggil "Satpam" Policy

        $request->validate([
            'name' => 'required',
            'territory_id' => 'required|exists:territories,id',
            'patron_saint' => 'nullable|string',
            'saint_image' => 'nullable|image|max:2048',
            'chief_name' => 'nullable|string',
            'info' => 'nullable|string',
        ]);

        $data = $request->except('saint_image');

        if ($request->hasFile('saint_image')) {
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
        $this->authorize('delete', $lingkungan); // Panggil "Satpam" Policy
        
        if ($lingkungan->saint_image && Storage::disk('public')->exists($lingkungan->saint_image)) {
            Storage::disk('public')->delete($lingkungan->saint_image);
        }
        
        $lingkungan->delete();

        return back()->with('success', 'Lingkungan berhasil dihapus.');
    }
}