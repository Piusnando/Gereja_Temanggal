@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Edit Pengumuman</h2>
    <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
            <input type="text" name="title" value="{{ $announcement->title }}" class="w-full border rounded p-2" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Acara</label>
            
            <input type="date" 
                name="event_date" 
                {{-- PERBAIKAN PENTING DI SINI: --}}
                {{-- 1. Gunakan old() agar jika error validasi, data tidak hilang --}}
                {{-- 2. Gunakan format('Y-m-d') agar terbaca oleh input date browser --}}
                value="{{ old('event_date', $announcement->event_date ? $announcement->event_date->format('Y-m-d') : '') }}" 
                class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                required>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
            <select name="category" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $announcement->category == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Isi Pengumuman</label>
            <textarea name="content" rows="4" class="w-full border rounded p-2" required>{{ $announcement->content }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto (Opsional)</label>
            @if($announcement->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $announcement->image_path) }}" class="h-24 rounded">
                </div>
            @endif
            <input type="file" name="image" class="w-full border rounded p-2 bg-gray-50">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.announcements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </div>

        <div class="mb-4 bg-yellow-50 p-3 rounded border border-yellow-200">
            <label class="inline-flex items-center cursor-pointer">
                <!-- Tambahkan {{ $announcement->is_pinned ? 'checked' : '' }} -->
                <input type="checkbox" name="is_pinned" value="1" class="form-checkbox h-5 w-5 text-yellow-600 rounded" 
                    {{ $announcement->is_pinned ? 'checked' : '' }}>
                <span class="ml-2 text-gray-700 font-bold">
                    <span class="mr-1">📌</span> Pin Pengumuman Ini?
                </span>
            </label>
            <p class="text-xs text-gray-500 mt-1 ml-7">Pengumuman yang di-pin akan selalu muncul di urutan paling atas halaman depan.</p>
        </div>
    </form>
</div>
@endsection