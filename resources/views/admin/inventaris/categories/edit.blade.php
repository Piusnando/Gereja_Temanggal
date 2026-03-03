@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-800">Edit Kategori</h2>
    </div>
    
    <form action="{{ route('admin.inventaris.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Kode Kategori (4 Huruf)</label>
            <input type="text" name="code" value="{{ old('code', $category->code) }}" class="w-full border rounded-lg p-2.5 uppercase font-mono tracking-widest focus:ring-2 focus:ring-blue-500" maxlength="4" required>
            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.inventaris.categories.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">Update</button>
        </div>
    </form>
</div>
@endsection