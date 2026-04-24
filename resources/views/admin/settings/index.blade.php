@extends('layouts.admin')

@section('content')

{{-- KOMPONEN NOTIFIKASI --}}
<div class="fixed top-5 right-5 z-50 space-y-4">
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         x-transition class="flex items-center bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl border-l-4 border-green-700">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <div>
            <h4 class="font-bold text-lg">Berhasil!</h4>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="ml-4 text-green-200 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
    </div>
    @endif

    @if($errors->any())
    <div x-data="{ show: true }" x-show="show" class="flex items-center bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl border-l-4 border-red-700">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h4 class="font-bold text-lg">Gagal!</h4>
            <ul class="text-sm list-disc list-inside">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        <button @click="show = false" class="ml-4 text-red-200 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
    </div>
    @endif
</div>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Tampilan Utama</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- CARD 1: UPLOAD LOGO -->
    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-600 w-full overflow-hidden">
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

        <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data" class="w-full">
            @csrf
            <label class="block mb-2 text-sm font-medium text-gray-700">Ganti Logo</label>
            <!-- Diperbaiki: Tambah overflow-hidden agar tidak tembus batas card di HP -->
            <div class="w-full overflow-hidden mb-4 border border-gray-300 rounded-lg bg-white">
                <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200 shadow-md">
                Simpan Logo
            </button>
        </form>
    </div>

    <!-- CARD 2: TAMBAH BANNER -->
    <div class="bg-white p-6 rounded-lg shadow-lg w-full overflow-hidden border border-gray-100">
        <h2 class="text-xl font-bold mb-4 text-blue-800">Tambah Banner Baru</h2>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul (Opsional)</label>
                <input type="text" name="title" class="block w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4 w-full overflow-hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Banner</label>
                <!-- Diperbaiki: File input di HP sering bikin layar jebol -->
                <input type="file" name="image" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Saran ukuran 1920x1080 px.</p>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-3 text-sm rounded mb-4">
                <p class="font-bold">Penting:</p>
                <p>Pastikan ukuran file foto maksimal <strong>2 MB</strong>.</p>
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full shadow-md">
                Upload Banner
            </button>
        </form>
    </div>
</div>

<!-- CARD 3: LIST BANNER -->
<div class="bg-white p-4 md:p-6 rounded-lg shadow-lg mt-8 relative w-full border border-gray-100">
    
    <!-- Diperbaiki: flex-col pada mobile agar tombol simpan turun ke bawah dan tidak tabrakan dengan judul -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Banner Slider</h2>
        
        <button type="submit" form="bulkUpdateForm" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            Simpan Semua Perubahan
        </button>
    </div>

    <!-- Hidden Bulk Form -->
    <form id="bulkUpdateForm" action="{{ route('admin.banners.update_all') }}" method="POST">
        @csrf
        @method('PUT')
    </form>

    <!-- Diperbaiki: Wrapper overflow dengan w-full -->
    <div class="overflow-x-auto w-full rounded-lg border border-gray-200">
        <!-- Diperbaiki: min-w-[700px] memaksa tabel tetap lebar di HP agar inputan tidak hancur tergencet -->
        <table class="min-w-[700px] w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="px-4 py-3 w-32">Gambar</th>
                    <th class="px-4 py-3">Judul & Urutan</th>
                    <th class="px-4 py-3 w-32">Status</th>
                    <th class="px-4 py-3 w-24 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($banners as $banner)
                <tr class="hover:bg-blue-50 transition duration-150">
                    <!-- Gambar -->
                    <td class="px-4 py-4 align-top">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="h-16 w-28 object-cover rounded-md border border-gray-200 shadow-sm">
                    </td>

                    <!-- Judul & Urutan -->
                    <td class="px-4 py-4 align-top">
                        <div class="mb-3">
                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Judul</label>
                            <input type="text" form="bulkUpdateForm" name="banners[{{ $banner->id }}][title]" value="{{ $banner->title }}" 
                                   class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Judul Banner">
                        </div>
                        <div class="flex items-center gap-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Urutan</label>
                                <input type="number" form="bulkUpdateForm" name="banners[{{ $banner->id }}][order]" value="{{ $banner->order }}" 
                                       class="w-20 border border-gray-300 rounded px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none text-center">
                            </div>
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-4 py-4 align-middle">
                        <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Visibilitas</label>
                        <select name="banners[{{ $banner->id }}][is_active]" form="bulkUpdateForm" 
                                class="w-full border border-gray-300 rounded px-2 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none {{ $banner->is_active ? 'text-green-700 bg-green-50' : 'text-gray-500 bg-gray-50' }}">
                            <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </td>

                    <!-- Hapus -->
                    <td class="px-4 py-4 align-middle text-center">
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Yakin hapus banner ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-700 p-2 rounded-lg transition shadow-sm" title="Hapus Banner">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-gray-400 bg-gray-50">
                        Belum ada banner terpasang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection