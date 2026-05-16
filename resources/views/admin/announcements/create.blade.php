@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Buat Pengumuman Baru</h2>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Ada kesalahan input!</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              category: '{{ old('category', count($categories) == 1 ? $categories[0] : '') }}',
              territories: {{ \Illuminate\Support\Js::from($territories) }},
              selectedWilayah: '{{ old('territory_id') }}'
          }">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
            <input type="text" name="title" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
            <input type="date" name="event_date" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
            <select name="category" x-model="category" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 bg-white" required>
                @if(count($categories) > 1) <option value="" disabled>-- Pilih Kategori --</option> @endif
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <!-- DEPENDENT DROPDOWN MUNCUL JIKA KATEGORI WILAYAH/LINGKUNGAN & BUKAN KETUA LINGKUNGAN -->
        @if(Auth::user()->role !== 'ketua_lingkungan')
        <div x-show="category === 'Wilayah' || category === 'Lingkungan'" x-transition class="mb-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
            <div class="mb-3">
                <label class="block text-blue-800 text-sm font-bold mb-1">Ditujukan Untuk Wilayah:</label>
                <select name="territory_id" x-model="selectedWilayah" class="w-full border rounded p-2 bg-white" :required="category === 'Wilayah' || category === 'Lingkungan'">
                    <option value="">-- Pilih Wilayah --</option>
                    <template x-for="t in territories" :key="t.id"><option :value="t.id" x-text="t.name"></option></template>
                </select>
            </div>
            
            <div x-show="category === 'Lingkungan'" x-transition>
                <label class="block text-blue-800 text-sm font-bold mb-1">Ditujukan Untuk Lingkungan:</label>
                <select name="lingkungan_id" class="w-full border rounded p-2 bg-white" :disabled="!selectedWilayah" :required="category === 'Lingkungan'">
                    <option value="">-- Pilih Lingkungan --</option>
                    <template x-for="t in territories"><template x-if="t.id == selectedWilayah">
                        <template x-for="l in t.lingkungans" :key="l.id"><option :value="l.id" x-text="l.name"></option></template>
                    </template></template>
                </select>
            </div>
        </div>
        @endif

        <div class="mb-4 bg-yellow-50 p-3 rounded border border-yellow-200">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_pinned" value="1" class="form-checkbox h-5 w-5 text-yellow-600 rounded">
                <span class="ml-2 text-gray-700 font-bold"><span class="mr-1">📌</span> Pin Pengumuman Ini?</span>
            </label>
            <p class="text-xs text-gray-500 mt-1 ml-7">Pengumuman akan selalu muncul di urutan atas.</p>
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