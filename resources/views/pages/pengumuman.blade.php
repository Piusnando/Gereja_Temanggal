@extends('layouts.main')

@section('title', 'Arsip Pengumuman - Gereja St. Ignatius Loyola')
@section('header', '')
<meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik, gereja di Sleman, Jadwal Misa, Pengumuman Gereja, Teritorial Wilayah, Organisasi Gereja, Petugas Liturgi, OMK, Misdinar, Lektor, Mazmur, Paduan Suara, Parkir Gereja, kalasan tengah, gereja yogyakarta, 
    gereja sleman, gereja di kalasan, paroki maria marganingsih kalasan, Gereja St. Ignatius Loyola Temanggal, Gereja Katolik di Kalasan, Jadwal Misa Kalasan, Pengumuman Gereja Kalasan, Teritorial Wilayah Kalasan, Organisasi Gereja Kalasan, Petugas Liturgi Kalasan, OMK Kalasan, Misdinar Kalasan, Lektor Kalasan, Mazmur Kalasan, Paduan Suara Kalasan, Parkir Gereja Kalasan,
    gereja temanggal, gereja di temanggal, paroki kalasan, Gereja St. Ignatius Loyola Kalasan Tengah Temanggal ')">

@section('content')
<div class="min-h-screen bg-neutral-100 pb-12">
    
    <!-- HEADER SECTION -->
    <div class="relative bg-logo-blue pt-16 pb-24 shadow-xl overflow-hidden">
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
                Temukan informasi kegiatan, jadwal liturgi, dan berita terkini dari Gereja St.Ignatius Temanggal.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- SEARCH & FILTER BAR (Floating Box) -->
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl mb-12 -mt-16 relative z-20 border border-gray-100">
            
            <!-- Tambahkan ID 'filterForm' untuk dipanggil Javascript -->
            <form id="filterForm" action="/pengumuman" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                
                <!-- Input Pencarian -->
                <div class="md:col-span-7">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Cari Kata Kunci</label>
                    <div class="relative group">
                        <!-- Tambahkan oninput="autoSearch()" -->
                        <input type="text" name="search" value="{{ $currentSearch }}" 
                               oninput="autoSearch()"
                               placeholder="Ketik untuk mencari (Otomatis)..." 
                               class="w-full border border-gray-300 rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-logo-blue focus:border-transparent transition shadow-sm group-hover:border-blue-300">
                        
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <!-- Icon Loading (Muncul saat mengetik - Optional CSS logic) -->
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-logo-blue transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Kategori -->
                <div class="md:col-span-5">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Filter Kategori</label>
                    <div class="relative">
                        <!-- Tambahkan onchange="this.form.submit()" -->
                        <select name="category" onchange="this.form.submit()" class="appearance-none w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-logo-blue bg-white shadow-sm cursor-pointer hover:border-blue-300 transition">
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

                <!-- Tombol Cari (Disembunyikan karena sudah otomatis, tapi tetap ada untuk aksesibilitas enter) -->
                <noscript>
                    <div class="md:col-span-12 mt-2">
                        <button type="submit" class="bg-logo-blue text-white px-4 py-2 rounded">Cari</button>
                    </div>
                </noscript>
            </form>
        </div>

        <!-- LIST PENGUMUMAN (GRID) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($announcements as $item)
            <a href="{{ route('pengumuman.detail', $item->id) }}" class="block bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 border border-gray-100 flex-col h-full group relative">

                <!-- BAGIAN GAMBAR -->
                <div class="h-64 w-full bg-gray-50 relative overflow-hidden flex items-center justify-center">
                    
                    <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://placehold.co/600x400/png?text=Gereja+Temanggal' }}" 
                        alt="{{ $item->title }}" 
                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                    
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
                    
                    <!-- Badge Kategori -->
                    <div class="absolute top-4 left-4 {{ $badgeColor }} text-white text-[10px] uppercase font-bold px-3 py-1 rounded-full shadow-md tracking-wider z-20">
                        {{ $item->category }}
                    </div>

                    <!-- ICON PIN (BARU) -->
                    @if($item->is_pinned)
                        <div class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full shadow-md z-20 flex items-center" title="Disematkan / Penting">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                PIN
                        </div>
                    @endif       
                </div>

                <!-- CONTENT -->
                <div class="p-6 flex flex-col grow">
                    <div class="flex items-center text-xs text-gray-500 mb-3 font-medium uppercase tracking-wide">
                        <svg class="w-4 h-4 mr-1 text-logo-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $item->event_date->translatedFormat('d F Y') }}
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-3 leading-snug group-hover:text-logo-blue transition line-clamp-2">
                        {{ $item->title }}
                    </h3>
                    
                    <p class="text-gray-600 text-sm line-clamp-3 mb-4 grow">
                        {{ Str::limit($item->content, 120) }}
                    </p>
                    
                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <span class="text-sm font-bold text-logo-red group-hover:text-red-800 flex items-center transition">
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-1 md:col-span-3 text-center py-16 bg-gray-50 rounded-xl border-dashed border-2 border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pengumuman</h3>
                <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian atau kategori filter Anda.</p>
                <a href="/pengumuman" class="mt-4 inline-block text-logo-blue font-bold hover:underline">Reset Filter</a>
            </div>
            @endforelse
        </div>

        <!-- PAGINATION (Otomatis muncul jika lebih dari 6 item karena settingan controller) -->
        <div class="mt-12">
            {{ $announcements->appends(['search' => $currentSearch, 'category' => $currentCategory])->links() }}
        </div>
    </div>
</div>

<!-- SCRIPT UNTUK AUTO SEARCH -->
<script>
    let searchTimeout;

    function autoSearch() {
        // Hapus timer sebelumnya jika user masih mengetik
        clearTimeout(searchTimeout);

        // Set timer baru: Form akan di-submit setelah user berhenti mengetik selama 800ms (0.8 detik)
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 800);
    }
</script>
@endsection