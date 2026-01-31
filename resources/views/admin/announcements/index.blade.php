@extends('layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Pengumuman</h1>
        <p class="text-sm text-gray-500">Buat dan atur pengumuman paroki/wilayah.</p>
    </div>
    
    <a href="{{ route('admin.announcements.create') }}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center justify-center transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Pengumuman
    </a>
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
    <span>{{ session('success') }}</span>
    <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">&times;</button>
</div>
@endif

{{-- FILTER BAR --}}
<div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-200">
    <form action="{{ route('admin.announcements.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        
        <!-- Search Input -->
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="w-full border border-gray-300 rounded pl-10 px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <!-- Filter Kategori & Reset -->
        <div class="flex flex-col sm:flex-row gap-4 md:w-auto w-full">
            @if(count($allowedCategories) > 1)
            <div class="w-full sm:w-48">
                <select name="category" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($allowedCategories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 font-medium text-center transition w-full sm:w-auto">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- TABEL DATA --}}
<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-bold border-b border-gray-200">
                <tr>
                    <!-- Kolom Tanggal & Foto disembunyikan di Mobile -->
                    <th class="px-5 py-3 text-left hidden md:table-cell w-32">Tanggal</th>
                    <th class="px-5 py-3 text-left hidden sm:table-cell w-20">Foto</th>
                    
                    <th class="px-5 py-3 text-left">Detail Pengumuman</th>
                    <th class="px-5 py-3 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($announcements as $item)
                <tr class="hover:bg-blue-50 transition">
                    
                    <!-- 1. KOLOM TANGGAL (Desktop Only) -->
                    <td class="px-5 py-4 whitespace-nowrap text-gray-500 hidden md:table-cell align-top">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-700">{{ $item->event_date->format('d M Y') }}</span>
                            <span class="text-xs">{{ $item->event_date->diffForHumans() }}</span>
                        </div>
                    </td>

                    <!-- 2. KOLOM FOTO (Desktop/Tablet Only) -->
                    <td class="px-5 py-4 hidden sm:table-cell align-top">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" class="h-12 w-12 rounded object-cover border border-gray-200">
                        @else
                            <div class="h-12 w-12 rounded bg-gray-100 flex items-center justify-center text-[10px] text-gray-400 font-bold border border-gray-200">No IMG</div>
                        @endif
                    </td>

                    <!-- 3. KOLOM JUDUL (Responsive Layout) -->
                    <td class="px-5 py-4 align-top">
                        <div class="flex gap-3">
                            <!-- Foto di Mobile (Muncul disamping judul) -->
                            <div class="sm:hidden shrink-0">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="h-12 w-12 rounded object-cover border border-gray-200">
                                @else
                                    <div class="h-12 w-12 rounded bg-gray-100 flex items-center justify-center text-[8px] text-gray-400 font-bold border border-gray-200">No IMG</div>
                                @endif
                            </div>

                            <div class="flex flex-col">
                                <!-- Tanggal di Mobile (Muncul diatas judul) -->
                                <span class="md:hidden text-xs text-gray-400 mb-1 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $item->event_date->format('d M Y') }}
                                </span>

                                <!-- Judul Utama -->
                                <p class="font-bold text-gray-800 text-base leading-tight mb-1">
                                    {{ $item->title }}
                                    @if($item->is_pinned)
                                        <span class="text-[10px] bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded ml-1 border border-yellow-200" title="Disematkan">📌 PIN</span>
                                    @endif
                                </p>
                                
                                <!-- Label Kategori -->
                                <div>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border
                                        {{ $item->category == 'Pengumuman Gereja' ? 'bg-blue-50 text-blue-700 border-blue-100' : 
                                          ($item->category == 'OMK' ? 'bg-orange-50 text-orange-700 border-orange-100' : 
                                          ($item->category == 'Berita Duka' ? 'bg-gray-800 text-white border-gray-600' : 'bg-gray-100 text-gray-600 border-gray-200')) }}">
                                        {{ $item->category }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- 4. KOLOM AKSI -->
                    <td class="px-5 py-4 text-center align-middle">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.announcements.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded transition border border-blue-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded transition border border-red-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-gray-500 bg-gray-50">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            <p>Belum ada pengumuman untuk kategori ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 bg-white border-t border-gray-200">
        {{ $announcements->appends(request()->query())->links() }}
    </div>
</div>

@endsection