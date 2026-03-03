<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvCategory;
use Illuminate\Http\Request;

class InvCategoryController extends Controller
{
    public function index()
    {
        $categories = InvCategory::latest()->paginate(10);
        return view('admin.inventaris.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.inventaris.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|alpha|size:4|unique:inv_categories,code',
        ],[
            'code.size' => 'Kode kategori HARUS persis 4 huruf.',
            'code.unique' => 'Kode kategori ini sudah dipakai.'
        ]);

        InvCategory::create([
            'name' => $request->name,
            'code' => strtoupper($request->code)
        ]);

        return redirect()->route('admin.inventaris.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(InvCategory $category)
    {
        return view('admin.inventaris.categories.edit', compact('category'));
    }

    public function update(Request $request, InvCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|alpha|size:4|unique:inv_categories,code,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'code' => strtoupper($request->code)
        ]);

        return redirect()->route('admin.inventaris.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(InvCategory $category)
    {
        if ($category->items()->count() > 0) {
            return back()->with('error', 'Gagal: Kategori ini sedang digunakan oleh data barang.');
        }

        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}