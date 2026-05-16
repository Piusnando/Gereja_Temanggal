@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Edit Pengumuman</h2>
    <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data"
          x-data="{ 
              category: '{{ old('category', $announcement->category) }}',
              territories: {{ \Illuminate\Support\Js::from($territories) }},
              selectedWilayah: '{{ old('territory_id', $announcement->territory_id) }}',
              selectedLingkungan: '{{ old('lingkungan_id', $announcement->lingkungan_id) }}'
          }">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
            <input type="text" name="title" value="{{ $announcement->title }}" class="w-full border rounded p-2" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Acara</label>
            <input type="date" name="event_date" value="{{ old('event_date', $announcement->event_date ? $announcement->event_date->format('Y-m-d') : '') }}" class="w-full border border-gray-300 rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
            <select name="category" x-model="category" class="w-full border rounded p-2 bg-white" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <!-- DEPENDENT DROPDOWN MUNCUL JIKA KATEGORI WILAYAH/LINGKUNGAN -->
        @if(Auth::user()->role !== 'ketua_lingkungan')
        <div x-show="category === 'Wilayah' || category === 'Lingkungan'" x-transition class="mb-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
            <div class="mb-3">
                <label class="block text-blue-800 text-sm font-bold mb-1">Ditujukan Untuk Wilayah:</label>
                <select name="territory_id" x-model="selectedWilayah" @change="selectedLingkungan = ''" class="w-full border rounded p-2 bg-white" :required="category === 'Wilayah' || category === 'Lingkungan'">
                    <option value="">-- Pilih Wilayah --</option>
                    <template x-for="t in territories" :key="t.id"><option :value="t.id" x-text="t.name" :selected="t.id == selectedWilayah"></option></template>
                </select>
            </div>
            
            <div x-show="category === 'Lingkungan'" x-transition>
                <label class="block text-blue-800 text-sm font-bold mb-1">Ditujukan Untuk Lingkungan:</label>
                <select name="lingkungan_id" x-model="selectedLingkungan" class="w-full border rounded p-2 bg-white" :disabled="!selectedWilayah" :required="category === 'Lingkungan'">
                    <option value="">-- Pilih Lingkungan --</option>
                    <template x-for="t in territories"><template x-if="t.id == selectedWilayah">
                        <template x-for="l in t.lingkungans" :key="l.id"><option :value="l.id" x-text="l.name" :selected="l.id == selectedLingkungan"></option></template>
                    </template></template>
                </select>
            </div>
        </div>
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Isi Pengumuman</label>
            <textarea name="content" rows="4" class="w-full border rounded p-2" required>{{ $announcement->content }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto (Opsional)</label>
            @if($announcement->image_path)
                <div class="mb-2"><img src="{{ asset('storage/' . $announcement->image_path) }}" class="h-24 rounded"></div>
            @endif
            <input type="file" name="image" class="w-full border rounded p-2 bg-gray-50">
        </div>

        <div class="mb-4 bg-yellow-50 p-3 rounded border border-yellow-200">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_pinned" value="1" class="form-checkbox h-5 w-5 text-yellow-600 rounded" {{ $announcement->is_pinned ? 'checked' : '' }}>
                <span class="ml-2 text-gray-700 font-bold"><span class="mr-1">📌</span> Pin Pengumuman Ini?</span>
            </label>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.announcements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </div>
    </form>
</div>
@endsection