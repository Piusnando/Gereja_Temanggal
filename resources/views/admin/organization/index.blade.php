@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Struktur Organisasi</h1>
        <p class="text-sm text-gray-500">Kelola data anggota berdasarkan bidang/seksi.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <!-- FORM FILTER KATEGORI -->
        <form action="{{ route('admin.organization.index') }}" method="GET" class="flex items-center">
            <div class="relative">
                <select name="category" onchange="this.form.submit()" class="appearance-none border border-gray-300 rounded-lg pl-4 pr-10 py-2 text-sm focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                    <option value="">-- Tampilkan Semua Bagian --</option>
                    @foreach($allowed as $cat)
                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </form>

        <a href="{{ route('admin.organization.create') }}" class="flex items-center justify-center bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Anggota
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm">
    <div class="flex">
        <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
        <div><p class="font-bold">Berhasil!</p><p class="text-sm">{{ session('success') }}</p></div>
    </div>
</div>
@endif

{{-- LOGIKA LOOPING: Tentukan kategori apa saja yang akan ditampilkan --}}
@php
    // Jika user memilih filter, gunakan hanya kategori itu. Jika tidak, gunakan semua kategori yang ada di $allowed.
    $categoriesToShow = $category ? [$category] : $allowed;
@endphp

<div class="space-y-8">
    @foreach($categoriesToShow as $currentCat)
        @php
            // Filter anggota berdasarkan kategori saat ini
            // Note: Pastikan Controller mengirim data yang cukup (sebaiknya pagination dimatikan atau dilimit besar jika ingin melihat struktur utuh)
            $catMembers = $members->filter(function($item) use ($currentCat) {
                return $item->category === $currentCat;
            });
        @endphp

        {{-- Hanya render section jika ada datanya, atau jika sedang dalam mode filter spesifik --}}
        @if($catMembers->count() > 0 || $category)
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <!-- Header Kategori -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2 h-6 bg-pink-500 rounded-full"></span>
                    {{ $currentCat }}
                </h2>
                <span class="text-xs font-semibold text-gray-500 bg-gray-200 px-2 py-1 rounded-md">
                    {{ $catMembers->count() }} Anggota
                </span>
            </div>

            <!-- Tabel Anggota -->
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead class="bg-white text-gray-600 uppercase text-xs font-semibold border-b">
                        <tr>
                            <th class="px-6 py-3 text-left tracking-wider">Foto</th>
                            <th class="px-6 py-3 text-left tracking-wider">Nama & Jabatan</th>
                            <th class="px-6 py-3 text-left tracking-wider">Lingkungan</th>
                            <th class="px-6 py-3 text-center tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                        @forelse($catMembers as $member)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <div class="shrink-0 h-10 w-10">
                                    @if($member->image)
                                        <img src="{{ asset('storage/' . $member->image) }}" class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" alt="{{ $member->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-linear-to-br from-gray-200 to-gray-300 flex items-center justify-center text-gray-600 font-bold text-xs shadow-sm">
                                            {{ substr($member->name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">{{ $member->name }}</span>
                                    <span class="text-xs text-gray-500 mt-0.5">{{ $member->position }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                @if($member->lingkungan)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $member->lingkungan->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-no-wrap text-sm font-medium">
                                <div class="flex justify-center gap-4">
                                    <a href="{{ route('admin.organization.edit', $member->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1 group">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span class="group-hover:underline">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.organization.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $member->name }} dari struktur?');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="if(confirm('Yakin hapus?')) this.closest('form').submit()" class="text-red-600 hover:text-red-900 flex items-center gap-1 group cursor-pointer bg-transparent border-0 p-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="group-hover:underline">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <p>Belum ada anggota di bagian <strong>{{ $currentCat }}</strong>.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endforeach
    
    <!-- Pagination (tetap ditampilkan jika controller menggunakan paginate) -->
    <div class="mt-4">
        {{ $members->appends(request()->query())->links() }}
    </div>
</div>
@endsection