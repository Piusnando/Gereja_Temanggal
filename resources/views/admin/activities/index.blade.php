@extends('layouts.admin')

@section('content')

{{-- HEADER HALAMAN --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Kegiatan</h1>
        <p class="text-sm text-gray-500">Daftar agenda dan berita kegiatan paroki.</p>
    </div>
    
    <a href="{{ route('admin.activities.create') }}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center justify-center transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Kegiatan Baru
    </a>
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
    <span>{{ session('success') }}</span>
    <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">&times;</button>
</div>
@endif

{{-- TABEL DATA --}}
<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-200">
                <tr>
                    <!-- Kolom Tanggal disembunyikan di Mobile -->
                    <th class="px-5 py-3 text-left hidden md:table-cell w-40">Tanggal</th>
                    
                    <th class="px-5 py-3 text-left">Judul Kegiatan</th>
                    
                    <!-- Kolom Penyelenggara disembunyikan di Mobile -->
                    <th class="px-5 py-3 text-left hidden sm:table-cell">Penyelenggara</th>
                    
                    <th class="px-5 py-3 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                @forelse($activities as $activity)
                <tr class="hover:bg-indigo-50 transition">
                    
                    <!-- 1. KOLOM TANGGAL (Desktop Only) -->
                    <td class="px-5 py-4 whitespace-nowrap hidden md:table-cell align-top">
                        <p class="font-bold text-gray-800">{{ $activity->start_time->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->start_time->format('H:i') }} WIB</p>
                    </td>

                    <!-- 2. KOLOM JUDUL (Responsive) -->
                    <td class="px-5 py-4 align-top">
                        <div class="flex flex-col">
                            <!-- Judul Utama -->
                            <p class="font-bold text-gray-800 text-base leading-tight mb-1">
                                {{ $activity->title }}
                            </p>
                            
                            <!-- Lokasi -->
                            <p class="text-xs text-gray-500 flex items-center mb-2">
                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $activity->location }}
                            </p>

                            <!-- INFO MOBILE ONLY (Tanggal & Penyelenggara muncul disini saat layar kecil) -->
                            <div class="md:hidden flex flex-wrap gap-2 mt-1">
                                <!-- Badge Tanggal Mobile -->
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $activity->start_time->format('d M y, H:i') }}
                                </span>

                                <!-- Badge Penyelenggara Mobile -->
                                <span class="sm:hidden inline-block bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded border border-indigo-200">
                                    {{ $activity->organizer }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- 3. KOLOM PENYELENGGARA (Desktop/Tablet Only) -->
                    <td class="px-5 py-4 hidden sm:table-cell align-top">
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full border border-indigo-200 shadow-sm">
                            {{ $activity->organizer }}
                        </span>
                    </td>

                    <!-- 4. KOLOM AKSI -->
                    <td class="px-5 py-4 text-center align-middle">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.activities.edit', $activity->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded transition border border-blue-100" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kegiatan ini?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded transition border border-red-100" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-gray-400 bg-gray-50">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            Belum ada data kegiatan. Silakan buat baru.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 bg-white border-t border-gray-200">
        {{ $activities->links() }}
    </div>
</div>
@endsection