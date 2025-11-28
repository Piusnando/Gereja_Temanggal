@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Tampilan Utama</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- CARD 1: UPLOAD LOGO -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4 text-blue-800">Logo Gereja</h2>
        
        @if($logo)
            <div class="mb-4 bg-gray-100 p-4 rounded text-center">
                <p class="text-sm text-gray-500 mb-2">Logo Saat Ini:</p>
                <img src="{{ asset('storage/' . $logo->value) }}" class="h-24 mx-auto object-contain">
            </div>
        @endif

        <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="block mb-2 text-sm font-medium text-gray-900">Ganti Logo</label>
            <input type="file" name="logo" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2">
            <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                Simpan Logo
            </button>
        </form>
    </div>

    <!-- CARD 2: TAMBAH BANNER -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4 text-blue-800">Tambah Banner Baru</h2>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Judul (Opsional)</label>
                <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Foto Banner</label>
                <input type="file" name="image" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2">
                <p class="text-xs text-gray-500 mt-1">Disarankan ukuran 1920x600 px.</p>
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                Upload Banner
            </button>
        </form>
    </div>

</div>

<!-- CARD 3: LIST BANNER -->
<div class="bg-white p-6 rounded-lg shadow-lg mt-8">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Daftar Banner Aktif</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Gambar</th>
                    <th class="px-6 py-3">Judul</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($banners as $banner)
                <tr class="bg-white border-b">
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="h-16 w-32 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        {{ $banner->title ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection