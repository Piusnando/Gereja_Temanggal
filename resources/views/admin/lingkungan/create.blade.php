@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah Lingkungan Baru</h2>
    
    <form action="{{ route('admin.lingkungan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Wilayah -->
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Wilayah</label>
            <select name="territory_id" class="w-full border rounded p-2.5 focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Pilih Wilayah --</option>
                @foreach($territories as $wil)
                    <option value="{{ $wil->id }}">{{ $wil->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Nama Lingkungan -->
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lingkungan</label>
            <input type="text" name="name" class="w-full border rounded p-2.5" placeholder="Contoh: St. Petrus" required>
        </div>

        <!-- Ketua Lingkungan -->
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Ketua Lingkungan</label>
            <input type="text" name="chief_name" class="w-full border rounded p-2.5">
        </div>

        <!-- Nama Pelindung & Foto -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Santo Pelindung</label>
                <input type="text" name="patron_saint" class="w-full border rounded p-2.5">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Foto Santo (Opsional)</label>
                <input type="file" name="saint_image" class="w-full border rounded p-2 text-sm bg-gray-50">
            </div>
        </div>

        <!-- Info Tambahan -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-1">Info Tambahan / Jadwal Doa</label>
            <textarea name="info" rows="3" class="w-full border rounded p-2.5"></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.lingkungan.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded">Batal</a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Simpan</button>
        </div>
    </form>
</div>
@endsection