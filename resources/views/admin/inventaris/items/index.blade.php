@extends('layouts.admin')

@section('content')
<!-- HEADER -->
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Data Barang Inventaris</h1>
        <p class="text-sm text-gray-500">Daftar semua aset dan peralatan gereja.</p>
    </div>
    
    <!-- PERUBAHAN DI SINI: Kita buat wrapper div untuk menampung 2 tombol -->
    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
        
        <!-- TOMBOL EXPORT EXCEL (BARU) -->
        <!-- request()->all() berfungsi untuk MENGIRIM FILTER SAAT INI ke controller export -->
        <a href="{{ route('admin.inventaris.items.export', request()->all()) }}" class="w-full sm:w-auto bg-green-100 text-green-700 hover:bg-green-600 hover:text-white border border-green-600 font-bold py-2 px-4 rounded-lg shadow-sm transition flex justify-center items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
            Export Excel
        </a>

        <!-- TOMBOL INPUT BARU (LAMA) -->
        <a href="{{ route('admin.inventaris.items.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition flex justify-center items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Barang
        </a>
    </div>
</div>

{{-- FORM FILTER & SEARCH --}}
<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6" x-data>
    <form action="{{ route('admin.inventaris.items.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        
        <!-- Filter Lokasi -->
        <div class="w-full md:w-1/4">
            <select name="location_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                <option value="">-- Semua Lokasi --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Kategori -->
        <div class="w-full md:w-1/4">
            <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Input Pencarian OTOMATIS -->
        <div class="w-full md:flex-1 relative">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Ketik nama / kode barang..." 
                   class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 transition"
                   x-on:input.debounce.1000ms="$el.form.submit()"
                   {{ request('search') ? 'autofocus' : '' }}
                   onfocus="var val=this.value; this.value=''; this.value= val;"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <!-- Indikator Loading -->
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center" style="display: none;" x-show="true" x-transition.opacity x-init="$el.style.display = 'none'" @input.window="$el.style.display = 'flex'; setTimeout(() => $el.style.display = 'none', 1000)">
                 <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
        </div>

        <!-- Tombol Reset -->
        <div class="w-full md:w-auto">
            @if(request()->anyFilled(['location_id', 'category_id', 'search']))
                <a href="{{ route('admin.inventaris.items.index') }}" class="flex items-center justify-center w-full px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition text-sm font-medium border border-gray-300">
                    Reset
                </a>
            @else
                <button type="submit" class="flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-sm">
                    Cari
                </button>
            @endif
        </div>
    </form>
</div>

{{-- Flash Messages --}}
@if(session('success')) 
    <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between">
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="text-green-700 font-bold">&times;</button>
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    
    <!-- TAMPILAN DESKTOP (TABLE) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left">Kode Barang</th>
                    <th class="px-5 py-3 text-left">Nama Barang</th>
                    <th class="px-5 py-3 text-left">Lokasi & Kategori</th>
                    <th class="px-5 py-3 text-center">Kondisi</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                @forelse($items as $item)
                <tr class="hover:bg-green-50 transition">
                    <!-- KODE BARANG -->
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="font-mono font-bold text-green-700 bg-green-50 px-2 py-1 rounded border border-green-200 text-xs">
                            {{ $item->item_code }}
                        </span>
                    </td>

                    <!-- NAMA BARANG -->
                    <td class="px-5 py-4">
                        <p class="font-bold text-gray-800 text-base">{{ $item->name }}</p>
                        @if($item->description)
                            <p class="text-xs text-gray-500 truncate max-w-xs">{{ $item->description }}</p>
                        @endif
                    </td>

                    <!-- LOKASI & KATEGORI -->
                    <td class="px-5 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center text-xs text-gray-600">
                                <svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $item->location->name }}
                            </span>
                            <span class="inline-flex items-center text-xs text-gray-600">
                                <svg class="w-3 h-3 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ $item->category->name }}
                            </span>
                        </div>
                    </td>

                    <!-- KONDISI -->
                    <td class="px-5 py-4 text-center">
                        @php
                            $condClass = match($item->condition) {
                                'Baik' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'Rusak Sedang' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'Rusak Berat' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $condClass }}">
                            {{ $item->condition }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="px-5 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.inventaris.items.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 p-2 rounded hover:bg-blue-100 transition border border-blue-100" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.inventaris.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus barang ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 p-2 rounded hover:bg-red-100 transition border border-red-100" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <p>Belum ada data barang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- TAMPILAN MOBILE (CARD) -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($items as $item)
        <div class="p-4 bg-white hover:bg-gray-50 transition">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="font-bold text-gray-800 text-base">{{ $item->name }}</p>
                    <span class="font-mono text-green-700 bg-green-50 px-2 py-0.5 text-xs rounded border border-green-200 mt-1 inline-block">{{ $item->item_code }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.inventaris.items.edit', $item->id) }}" class="p-2 bg-blue-50 rounded border border-blue-100 text-blue-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                    <form action="{{ route('admin.inventaris.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');"> @csrf @method('DELETE') <button type="submit" class="p-2 bg-red-50 rounded border border-red-100 text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button> </form>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600 mt-2">
                <!-- Kondisi -->
                @php $condClass = match($item->condition) { 'Baik'=>'bg-blue-100 text-blue-800','Rusak Sedang'=>'bg-yellow-100 text-yellow-800','Rusak Berat'=>'bg-red-100 text-red-800', default=>'bg-gray-100'}; @endphp
                <span class="px-2 py-0.5 rounded-full font-bold {{ $condClass }}">{{ $item->condition }}</span>
                
                <!-- Lokasi -->
                <span class="flex items-center"><svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $item->location->name }}</span>
                
                <!-- Kategori -->
                <span class="flex items-center"><svg class="w-3 h-3 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> {{ $item->category->name }}</span>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-400 bg-gray-50">
            <p>Belum ada data barang.</p>
        </div>
        @endforelse
    </div>
    
    <div class="p-4 bg-gray-50 border-t border-gray-100">
        {{ $items->links() }}
    </div>
</div>
@endsection