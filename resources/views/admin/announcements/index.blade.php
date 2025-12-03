@extends('layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Pengumuman</h1>
    
    <a href="{{ route('admin.announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Pengumuman
    </a>
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">{{ session('success') }}</div>
@endif

{{-- FILTER BAR (BARU) --}}
<div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-200">
    <form action="{{ route('admin.announcements.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        
        <!-- Search Input -->
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul pengumuman..." class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Filter Kategori (Dinamis Sesuai Role) -->
        <!-- Hanya muncul jika user punya akses ke LEBIH DARI 1 kategori (misal: Admin) -->
        @if(count($allowedCategories) > 1)
        <div class="w-full md:w-1/4">
            <select name="category" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Semua Kategori Saya</option>
                @foreach($allowedCategories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Tombol Reset -->
        <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 font-medium text-center">
            Reset
        </a>
    </form>
</div>

{{-- TABEL DATA --}}
<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-bold border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left">Tanggal</th>
                <th class="px-5 py-3 text-left">Foto</th>
                <th class="px-5 py-3 text-left">Judul & Kategori</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
            @forelse($announcements as $item)
            <tr class="hover:bg-blue-50 transition">
                <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                    {{ $item->event_date->format('d M Y') }}
                </td>
                <td class="px-5 py-4">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" class="h-10 w-10 rounded object-cover border border-gray-200">
                    @else
                        <div class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center text-xs text-gray-400">No IMG</div>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <p class="font-bold text-gray-800 text-base">{{ $item->title }}</p>
                    
                    <!-- Label Kategori -->
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold
                        {{ $item->category == 'Pengumuman Gereja' ? 'bg-blue-100 text-blue-800' : 
                          ($item->category == 'OMK' ? 'bg-orange-100 text-orange-800' : 
                          ($item->category == 'Berita Duka' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $item->category }}
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.announcements.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-10 text-center text-gray-500 bg-gray-50">
                    Belum ada pengumuman untuk kategori ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 bg-white border-t border-gray-200">
        {{ $announcements->appends(request()->query())->links() }}
    </div>
</div>

@endsection