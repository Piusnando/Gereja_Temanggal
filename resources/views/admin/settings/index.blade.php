@extends('layouts.admin')

@section('content')

{{-- ========================================================== --}}
{{-- KOMPONEN NOTIFIKASI (TOAST) --}}
{{-- Menggunakan Alpine.js untuk animasi dan auto-close --}}
{{-- ========================================================== --}}
<div class="fixed top-5 right-5 z-50 space-y-4">
    
    <!-- Notifikasi Sukses -->
    @if(session('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-8"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-8"
         class="flex items-center bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl border-l-4 border-green-700">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <div>
            <h4 class="font-bold text-lg">Berhasil!</h4>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="ml-4 text-green-200 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <!-- Notifikasi Error -->
    @if($errors->any())
    <div x-data="{ show: true }"
         x-show="show"
         class="flex items-center bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl border-l-4 border-red-700">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h4 class="font-bold text-lg">Gagal!</h4>
            <ul class="text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="ml-4 text-red-200 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

</div>
{{-- ========================================================== --}}


<h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Tampilan Utama</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- CARD 1: UPLOAD LOGO -->
    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-600">
        <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Logo Gereja
        </h2>
        
        @if(isset($logo) && $logo)
            <div class="mb-4 bg-gray-50 p-4 rounded text-center border border-gray-200">
                <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide">Logo Saat Ini</p>
                <img src="{{ asset('storage/' . $logo->value) }}" class="h-24 mx-auto object-contain">
            </div>
        @endif

        <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="block mb-2 text-sm font-medium text-gray-700">Ganti Logo</label>
            <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-4 border border-gray-300 rounded-lg cursor-pointer bg-white">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200 shadow-md transform hover:-translate-y-0.5">
                Simpan Logo
            </button>
        </form>
    </div>

    <!-- CARD 2: TAMBAH BANNER -->
    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-green-600">
        <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Banner Baru
        </h2>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul (Opsional)</label>
                <input type="text" name="title" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 p-2 border" placeholder="Contoh: Selamat Datang">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Banner</label>
                <input type="file" name="image" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg cursor-pointer bg-white">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Disarankan rasio landscape (16:9).</p>
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200 shadow-md transform hover:-translate-y-0.5">
                Upload Banner
            </button>
        </form>
    </div>
</div>

<!-- CARD 3: DAFTAR BANNER -->
<div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Daftar Banner Aktif
        </h2>
        <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full">
            {{ isset($banners) ? count($banners) : 0 }} Slide
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($banners as $banner)
        <div class="group relative bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
            <!-- Gambar -->
            <div class="aspect-w-16 aspect-h-9 h-48 bg-gray-100 relative">
                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" class="w-full h-full object-cover">
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-linear-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
            </div>
            
            <!-- Info -->
            <div class="p-4">
                <p class="font-bold text-gray-800 truncate text-lg" title="{{ $banner->title }}">
                    {{ $banner->title ?: 'Tanpa Judul' }}
                </p>
                <div class="flex items-center mt-2 text-xs text-gray-500">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $banner->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Tombol Hapus (Muncul saat hover) -->
            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition duration-300">
                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus banner ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-white hover:bg-red-600 text-red-600 hover:text-white p-2 rounded-full shadow-lg transition duration-200 transform hover:scale-110" title="Hapus Banner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-3 text-center py-16 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-gray-500 text-lg font-medium">Belum ada banner yang diupload.</p>
            <p class="text-gray-400 text-sm">Upload gambar di atas untuk menampilkannya di halaman depan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection