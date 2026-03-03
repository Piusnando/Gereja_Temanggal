<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvItemExport;
use App\Http\Controllers\Controller;
use App\Models\InvCategory;
use App\Models\InvItem;
use App\Models\InvLocation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvItemController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data master untuk Dropdown Filter
        $locations = InvLocation::orderBy('name')->get();
        $categories = InvCategory::orderBy('name')->get();

        // 2. Mulai Query
        $query = InvItem::with(['location', 'category']);

        // 3. Logika Filter Lokasi
        if ($request->filled('location_id')) {
            $query->where('inv_location_id', $request->location_id);
        }

        // 4. Logika Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('inv_category_id', $request->category_id);
        }

        // 5. Logika Pencarian Nama/Kode (Opsional tapi bagus ada)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        // 6. Ambil Data (Pagination)
        // Gunakan appends() agar parameter filter tidak hilang saat klik page 2
        $items = $query->latest()->paginate(15)->appends($request->all());

        return view('admin.inventaris.items.index', compact('items', 'locations', 'categories'));
    }

    public function create()
    {
        $locations = InvLocation::orderBy('name')->get();
        $categories = InvCategory::orderBy('name')->get();
        return view('admin.inventaris.items.create', compact('locations', 'categories'));
    }

    public function edit($id)
    {
        $item = InvItem::findOrFail($id);
        $locations = InvLocation::orderBy('name')->get();
        $categories = InvCategory::orderBy('name')->get();
        return view('admin.inventaris.items.edit', compact('item', 'locations', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = InvItem::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'inv_location_id' => 'required|exists:inv_locations,id',
            'inv_category_id' => 'required|exists:inv_categories,id',
            'serial_number' => 'required|string|max:50',
            'condition' => 'required|in:Baik,Rusak Sedang,Rusak Berat',
        ]);

        // GENERATE KODE BARU
        $location = InvLocation::find($request->inv_location_id);
        $category = InvCategory::find($request->inv_category_id);
        $newItemCode = strtoupper("GSTI_{$location->code}_{$category->code}_{$request->serial_number}");

        // Cek jika kode berubah dan sudah ada yg pakai (kecuali punya barang ini sendiri)
        if ($newItemCode !== $item->item_code && InvItem::where('item_code', $newItemCode)->exists()) {
            return back()->withInput()->with('error', "Kode Barang {$newItemCode} sudah digunakan oleh barang lain!");
        }

        $item->update([
            'name' => $request->name,
            'inv_location_id' => $request->inv_location_id,
            'inv_category_id' => $request->inv_category_id,
            'serial_number' => strtoupper($request->serial_number),
            'item_code' => $newItemCode, // Kode otomatis terupdate
            'condition' => $request->condition,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.inventaris.items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'inv_location_id' => 'required|exists:inv_locations,id',
            'inv_category_id' => 'required|exists:inv_categories,id',
            'serial_number' => 'required|string|max:50',
            'condition' => 'required|in:Baik,Rusak Sedang,Rusak Berat',
        ]);

        // AMBIL KODE LOKASI DAN KATEGORI
        $location = InvLocation::find($request->inv_location_id);
        $category = InvCategory::find($request->inv_category_id);

        // GENERATE KODE BARANG FULL (Format: GSTI_LOKASI_KATEGORI_NOSERI)
        // Gunakan strtoupper agar pasti huruf besar semua
        $itemCode = strtoupper("GSTI_{$location->code}_{$category->code}_{$request->serial_number}");

        // Validasi agar kode tidak dobel
        if (InvItem::where('item_code', $itemCode)->exists()) {
            return back()->withInput()->with('error', "Kode Barang {$itemCode} sudah terdaftar!");
        }

        InvItem::create([
            'name' => $request->name,
            'inv_location_id' => $request->inv_location_id,
            'inv_category_id' => $request->inv_category_id,
            'serial_number' => strtoupper($request->serial_number),
            'item_code' => $itemCode,
            'condition' => $request->condition,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.inventaris.items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $item = InvItem::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Barang berhasil dihapus dari inventaris.');
    }

    public function export(Request $request) 
    {
        // Generate nama file dengan tanggal hari ini
        $fileName = 'Data_Inventaris_Gereja_' . date('Y-m-d') . '.xlsx';
        
        // Memanggil class Export dan melemparkan $request (yg berisi data filter)
        return Excel::download(new InvItemExport($request->all()), $fileName);
    }
}