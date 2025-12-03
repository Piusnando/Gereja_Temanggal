@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Anggota</h1>
        <p class="text-sm text-gray-500">Perbarui data pengurus organisasi.</p>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
        <form action="{{ route('admin.organization.update', $member->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Wajib untuk Update -->
            
            <!-- 1. KATEGORI -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Organisasi</label>
                <select name="category" class="block w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ (old('category', $member->category) == $cat) ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 2. FOTO PROFIL -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil</label>
                
                <!-- Preview Foto Lama -->
                @if($member->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $member->image) }}" class="w-20 h-20 rounded-full object-cover border border-gray-300">
                        <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
                    </div>
                @endif

                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg">
                <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti foto.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 3. NAMA LENGKAP -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" class="block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- 4. JABATAN -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan</label>
                <input type="text" name="position" value="{{ old('position', $member->position) }}" class="block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- 5. ASAL LINGKUNGAN -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Asal Lingkungan</label>
                <select name="lingkungan_id" class="block w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="" disabled>-- Pilih Lingkungan --</option>
                    @foreach($lingkungans as $l)
                        <option value="{{ $l->id }}" {{ (old('lingkungan_id', $member->lingkungan_id) == $l->id) ? 'selected' : '' }}>
                            {{ $l->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.organization.index', ['category' => $member->category]) }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-bold transition text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition text-sm shadow-md">Update Data</button>
            </div>

        </form>
    </div>
</div>
@endsection