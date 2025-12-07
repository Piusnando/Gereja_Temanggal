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
        
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
            <input type="date" name="event_date" class="w-full border rounded p-2" required>
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
    </form>
</div>
@endsection