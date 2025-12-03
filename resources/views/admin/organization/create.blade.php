@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Anggota Baru</h1>
        <p class="text-sm text-gray-500">Menambahkan pengurus ke dalam struktur organisasi.</p>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
        <!-- PENTING: Tambahkan enctype agar bisa upload file -->
        <form action="{{ route('admin.organization.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- 1. KATEGORI -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Organisasi</label>
                <div class="relative">
                    <select name="category" class="block w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 appearance-none" required>
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ (old('category') ?? $category) == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <!-- 2. FOTO PROFIL (BARU) -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil (Opsional)</label>
                <input type="file" name="image" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2.5 file:px-4
                    file:rounded-l-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 3. NAMA LENGKAP -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- 4. JABATAN -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan</label>
                <input type="text" name="position" value="{{ old('position') }}" class="block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Ketua, Anggota" required>
            </div>

            <!-- 5. ASAL LINGKUNGAN -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Asal Lingkungan</label>
                <div class="relative">
                    <select name="lingkungan_id" class="block w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 appearance-none" required>
                        <option value="" disabled selected>-- Pilih Lingkungan --</option>
                        @foreach($lingkungans as $l)
                            <option value="{{ $l->id }}" {{ old('lingkungan_id') == $l->id ? 'selected' : '' }}>
                                {{ $l->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.organization.index', ['category' => $category]) }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-bold transition text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition text-sm shadow-md">Simpan Data</button>
            </div>

        </form>
    </div>
</div>
@endsection