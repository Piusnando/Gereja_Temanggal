@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Buat Pengumuman Baru</h2>
    <!-- Cek Error Validasi -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Ada kesalahan input!</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
            <input type="text" name="title" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
            <!-- Perhatikan name="event_date" -->
            <input type="date" name="event_date" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
            <select name="category" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                
                <!-- Jika kategorinya lebih dari 1 (Admin), tampilkan opsi default -->
                @if(count($categories) > 1)
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                @endif

                <!-- Loop kategori sesuai hak akses -->
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ (count($categories) == 1) ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach

            </select>
            @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Isi Pengumuman</label>
            <textarea name="content" rows="4" class="w-full border rounded p-2" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Foto (Opsional)</label>
            <input type="file" name="image" class="w-full border rounded p-2 bg-gray-50">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.announcements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection