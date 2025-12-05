@extends('layouts.main')

@section('title', 'Arsip Pengumuman - Gereja St. Ignatius Loyola')
@section('header', '')

@section('content')
<div class="min-h-screen bg-neutral-100 pb-12">
    
    <!-- HEADER SECTION -->
    <div class="relative bg-logo-blue pt-16 pb-24 shadow-xl overflow-hidden">
        <!-- Pattern Hiasan Background (Opsional) -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z"/>
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight drop-shadow-sm">
                Arsip Pengumuman & Berita
            </h1>
            <p class="text-blue-100 text-lg md:text-xl font-medium max-w-2xl mx-auto leading-relaxed">
                Temukan informasi kegiatan, jadwal liturgi, dan berita terkini dari Paroki Maria Marganingsih Kalasan.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- SEARCH & FILTER BAR (Floating Box) -->
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl mb-12 -mt-16 relative z-20 border border-gray-100">
            <form action="/pengumuman" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                <!-- Input Pencarian -->
                <div class="md:col-span-5">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Cari Kata Kunci</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Contoh: Natal, Rapat, Berita Duka..." 
                               class="w-full border border-gray-300 rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-logo-blue focus:border-transparent transition shadow-sm group-hover:border-blue-300">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-logo-blue transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Kategori -->
                <div class="md:col-span-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Filter Kategori</label>
                    <div class="relative">
                        <select name="category" class="appearance-none w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-logo-blue bg-white shadow-sm cursor-pointer hover:border-blue-300 transition">
                            <option value="">Semua Kategori</option>
                            @foreach([
                                'Pengumuman Gereja', 'Paroki', 'Wilayah', 'Lingkungan', 
                                'OMK', 'Misdinar', 'PIA/PIR', 'Calon Manten', 'Berita Duka'
                            ] as $cat)
                                <option value="{{ $cat }}" {{ $currentCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Tombol Cari -->
                <div class="md:col-span-3 flex items-end">
                    <button type="submit" class="w-full bg-logo-red hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 shadow-md transform hover:-translate-y-1 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- LIST PENGUMUMAN (GRID) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($announcements as $item)
                <!-- CARD WRAPPER: Tambahkan 'group', 'hover:-translate-y-2', 'hover:shadow-2xl' -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 flex flex-col h-full group hover:-translate-y-2 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 ease-in-out cursor-pointer">
                    
                    <!-- FOTO -->
                    <div class="h-52 w-full bg-gray-200 relative overflow-hidden">
                        <!-- GAMBAR: Tambahkan 'group-hover:scale-110' dan ganti placeholder -->
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://placehold.co/500x300?text=Gereja+Temanggal' }}" 
                            alt="{{ $item->title }}" 
                            class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-in-out">
                        
                        <!-- Overlay Gelap Tipis saat Hover -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>

                        <!-- Badge Kategori -->
                        @php
                            $colors = [
                                'Pengumuman Gereja' => 'bg-blue-600',
                                'Paroki'            => 'bg-indigo-700',
                                'Wilayah'           => 'bg-emerald-600',
                                'Lingkungan'        => 'bg-green-600',
                                'OMK'               => 'bg-orange-500',
                                'Misdinar'          => 'bg-red-600',
                                'PIA/PIR'           => 'bg-yellow-500',
                                'Calon Manten'      => 'bg-pink-500',
                                'Berita Duka'       => 'bg-gray-800',
                            ];
                            $badgeColor = $colors[$item->category] ?? 'bg-blue-500';
                        @endphp
                        <div class="absolute top-4 left-4 {{ $badgeColor }} text-white text-[10px] uppercase font-bold px-3 py-1 rounded-full shadow-md tracking-wider z-10">
                            {{ $item->category }}
                        </div>
                    </div>

                    <!-- KONTEN -->
                    <div class="p-6 flex flex-col grow relative">
                        <!-- Tanggal -->
                        <div class="flex items-center text-xs text-gray-500 mb-3 font-medium uppercase tracking-wide">
                            <svg class="w-4 h-4 mr-1 text-logo-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $item->event_date->translatedFormat('d F Y') }}
                        </div>

                        <!-- Judul: Berubah warna saat hover -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-snug group-hover:text-logo-blue transition-colors duration-300 line-clamp-2">
                            {{ $item->title }}
                        </h3>
                        
                        <!-- Deskripsi -->
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 grow">
                            {{ Str::limit($item->content, 120) }}
                        </p>
                        
                        <!-- Link Baca Selengkapnya -->
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <a href="{{ route('pengumuman.detail', $item->id) }}" class="inline-flex items-center text-sm font-bold text-logo-red hover:text-red-800 transition">
                                Baca Selengkapnya
                                <!-- Icon Panah: Bergerak saat hover -->
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-3 text-center py-20 bg-white rounded-xl border border-gray-200 border-dashed">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada pengumuman ditemukan</h3>
                    <p class="mt-1 text-gray-500">Coba ubah kata kunci pencarian atau kategori filter Anda.</p>
                    <a href="/pengumuman" class="mt-4 inline-block text-logo-blue font-bold hover:underline">Reset Filter</a>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="mt-12">
            {{ $announcements->appends(['search' => $currentSearch, 'category' => $currentCategory])->links() }}
        </div>
    </div>
</div>
@endsection