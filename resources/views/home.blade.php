@extends('layouts.main')

@section('title', 'Beranda - Gereja St. Ignatius Loyola Temanggal')

@section('content')

    {{-- SECTION 1: HERO BANNER (FIXED COMPOSITION) --}}
    <div x-data="{ 
            activeSlide: 1, 
            slides: [
                @foreach($banners as $banner)
                    '{{ asset('storage/' . $banner->image_path) }}',
                @endforeach
            ],
            loop() { 
                this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1 
            },
            timer: null
        }" 
        x-init="timer = setInterval(() => { loop() }, 5000)"
        class="relative w-full group mb-12 -mt-px" 
        style="height: calc(100vh - 6rem);"> 
        
        <div class="absolute inset-0 overflow-hidden shadow-2xl border-b-8 border-logo-red bg-gray-900">
            
            @if($banners->isEmpty())
                <div class="absolute inset-0 flex items-center justify-center text-gray-500">
                    Belum ada banner yang diupload.
                </div>
            @endif

            <!-- Loop Images -->
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index + 1"
                     class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out"
                     x-transition:enter="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="opacity-100"
                     x-transition:leave-end="opacity-0"
                     
                     {{-- 
                        ============================================
                        PERBAIKAN UTAMA: Gunakan 'bg-top'
                        Ini akan "mengunci" gambar di bagian atasnya,
                        memastikan kepala patung tidak pernah terpotong.
                        ============================================
                     --}}
                     :class="'bg-cover bg-top'"
                     
                     :style="`background-image: url('${slide}')`">
                     
                     <!-- Overlay Gelap (Gradasi dari Bawah) -->
                     <div class="absolute inset-0 bg-black/40"></div>
                </div>
            </template>

            <!-- Teks Tengah (Digeser ke Bawah) -->
            {{-- 
                ============================================
                PERBAIKAN UTAMA: Gunakan 'items-end pb-20'
                Ini akan mendorong semua teks ke bagian bawah banner,
                memberi ruang visual untuk patung di bagian atas.
                ============================================
            --}}
            <div class="absolute inset-0 flex items-end justify-center text-center px-4 z-10 pb-20 md:pb-24">
                <div class="max-w-4xl drop-shadow-lg pointer-events-auto">
                    <h2 class="text-sm md:text-xl font-bold uppercase tracking-[0.2em] mb-4 text-logo-yellow animate-bounce">
                        Selamat Datang di Website Resmi
                    </h2>
                    <h1 class="text-4xl md:text-7xl font-extrabold leading-tight mb-6 text-white drop-shadow-xl">
                        Gereja St. Ignatius<br>
                        <span class="text-red-500">Temanggal</span>
                    </h1>
                    <p class="text-lg md:text-2xl font-medium text-gray-100 mt-2 bg-black/30 inline-block px-8 py-3 rounded-full backdrop-blur-sm border border-white/20">
                        Paroki Maria Marganingsih Kalasan
                    </p>
                    <div class="mt-12">
                        <a href="#jadwal-misa" class="bg-logo-red hover:bg-red-800 text-white text-lg font-bold py-4 px-10 rounded-full transition shadow-lg border-2 border-transparent hover:border-white hover:scale-105 transform duration-300 inline-flex items-center">
                            Lihat Jadwal Misa
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- SECTION 2: PENGUMUMAN TERBARU --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Pengumuman Terbaru</h2>
                <p class="text-gray-500 mt-1">Informasi kegiatan, liturgi, dan berita gereja.</p>
            </div>
            <a href="/pengumuman" class="mt-4 md:mt-0 inline-flex items-center text-logo-blue hover:text-blue-800 font-bold transition">
                Lihat Arsip
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($announcements as $item)
                    <a href="{{ route('pengumuman.detail', $item->id) }}" class="block bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 border border-gray-100 flex-col h-full group relative">
                        
                        <!-- BAGIAN GAMBAR -->
                        <div class="h-64 w-full bg-gray-50 relative overflow-hidden flex items-center justify-center">
                            
                            <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://placehold.co/600x400/png?text=Gereja+Temanggal' }}" 
                                alt="{{ $item->title }}" 
                                class="w-full h-full object-contain transform group-hover:scale-105 transition duration-700">
                            
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
                    </div>
                    @endforelse
                </div>
        </div>
        
    {{-- ======================================== --}}
    {{-- SECTION BARU: BERITA KEGIATAN (POST-EVENT) --}}
    {{-- ======================================== --}}
    <div class="bg-white py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-100 pb-4">
                <div class="text-left">
                    <span class="text-logo-blue font-bold tracking-widest uppercase text-sm">Dokumentasi & Laporan</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-2">Berita Kegiatan Ignatius Temanggal</h2>
                </div>
                <!-- UPDATE LINK LIHAT SEMUA -->
                <a href="{{ route('kegiatan.index') }}" class="mt-4 md:mt-0 text-logo-blue font-bold hover:text-logo-red transition flex items-center">
                    Lihat Semua Kegiatan <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($activityNews as $news)
                <!-- UPDATE: Tambahkan pembungkus <a> ke detail agar seluruh kartu bisa diklik -->
                <a href="{{ route('kegiatan.detail', $news->id) }}" class="group bg-white rounded-xl overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 flex flex-col h-full">
                    
                    <!-- Foto Kegiatan -->
                    <div class="h-48 overflow-hidden relative bg-gray-100">
                        <img src="{{ $news->image_path ? asset('storage/' . $news->image_path) : 'https://placehold.co/600x400?text=Kegiatan' }}" 
                             alt="{{ $news->title }}" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute bottom-0 left-0 bg-linear-to-t from-black/80 to-transparent w-full p-4 pt-10">
                            <span class="text-white text-[10px] font-bold uppercase tracking-wider bg-logo-red px-2 py-1 rounded shadow-sm">
                                {{ $news->organizer }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Isi Berita -->
                    <div class="p-6 flex flex-col grow">
                        <div class="flex items-center text-xs text-gray-500 mb-3">
                            <svg class="w-4 h-4 mr-1 text-logo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $news->start_time->translatedFormat('d F Y') }}
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-logo-blue transition line-clamp-2">
                            {{ $news->title }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 grow">
                            {{ Str::limit(strip_tags($news->description), 100) }}
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center text-logo-blue font-bold text-sm">
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-3 text-center py-12 bg-gray-50 rounded-xl border-dashed border-2 border-gray-200">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <p class="text-gray-500 italic">Belum ada berita kegiatan terbaru.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ======================================== --}}
    {{-- SECTION 3: LOKASI & JADWAL MISA --}}
    {{-- ======================================== --}}
    <div class="py-16 bg-linear-to-b from-gray-50 to-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <!-- KOLOM KIRI: Peta Google Maps (KEMBALI NORMAL) -->
                <div class="flex flex-col h-full">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="bg-red-100 text-logo-red p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        </span>
                        Lokasi Gereja
                    </h2>
                    
                    <div class="bg-white p-2 rounded-2xl shadow-lg border border-gray-200 grow min-h-[400px]">
                        <iframe 
                            src="https://maps.google.com/maps?q=Gereja+Katolik+Santo+Ignatius+Loyola+Temanggal+Sleman+Yogyakarta&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                            width="100%" height="100%" style="border:0; border-radius: 1rem;" allowfullscreen="" loading="lazy"
                            class="shadow-inner" title="Peta Lokasi Gereja">
                        </iframe>
                    </div>
                    
                    <div class="mt-6 flex items-start p-4 bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                        <svg class="w-6 h-6 mt-1 mr-4 text-logo-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div>
                            <h4 class="font-bold text-gray-900">Alamat Lengkap</h4>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">
                                Jl. Temanggal Raya, Temanggal, Purwomartani, Kec. Kalasan, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55571.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Jadwal & Teritorial -->
                <div class="flex flex-col space-y-8">
                    
                    <!-- Box Jadwal Misa -->
                    <div id="jadwal-misa" class="scroll-mt-32 bg-white rounded-2xl shadow-xl p-8 border-t-8 border-logo-red relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-red-50 rounded-full opacity-50 transition group-hover:scale-110"></div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center relative z-10">
                            <span class="bg-red-100 text-logo-red p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Jadwal Misa
                        </h2>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between bg-linear-to-r from-red-50 to-white rounded-xl p-5 border border-red-100 hover:border-red-200 transition">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">Misa Mingguan (Sabtu)</h3>
                                    <p class="text-logo-red text-sm font-medium">Gereja St. Ignatius Loyola</p>
                                </div>
                                <div class="text-right">
                                    <span class="block text-3xl font-black text-gray-800 tracking-tight">18.00</span>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">WIB</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-center text-sm text-gray-500 bg-gray-50 p-3 rounded-lg">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Umat dimohon hadir 30 menit sebelum misa dimulai.
                        </div>
                    </div>

                    <!-- Box Teritorial -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border-t-8 border-logo-blue grow relative overflow-hidden flex flex-col h-full">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-blue-100 text-logo-blue p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Teritorial Gereja
                        </h2>
                        
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Wilayah pelayanan Pastoral di Gereja St. Ignatius Loyola mencakup 
                            <strong>{{ $territories->count() }} Wilayah</strong> utama.
                        </p>

                        <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100 grow">
                            <ul class="grid grid-cols-1 gap-3 text-sm text-gray-700 font-medium">
                                @forelse($territories as $wilayah)
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-logo-blue rounded-full mr-2 mt-1.5 shrink-0"></span>
                                        <span class="leading-tight">Wilayah {{ $wilayah->name }}</span>
                                    </li>
                                @empty
                                    <li class="text-gray-400 italic">Data wilayah belum tersedia.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mt-8">
                            <a href="/teritorial" class="block w-full text-center py-3 bg-white border-2 border-logo-blue text-logo-blue rounded-xl font-bold hover:bg-logo-blue hover:text-white transition duration-300 shadow-sm">
                                Lihat Peta Wilayah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ======================================== --}}
    {{-- SECTION 4: KALENDER LITURGI --}}
    {{-- ======================================== --}}
    <div class="py-20 bg-gray-50 border-t border-gray-200 overflow-hidden relative">
        <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/black-scales.png')] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    
                    <!-- Kiri: Info Tanggal Hari Ini -->
                    <div class="p-10 md:p-16 flex flex-col justify-center bg-logo-blue text-white relative">
                        
                        <!-- UPDATE: BACKGROUND ALKITAB/BUKU -->
                        <svg class="absolute right-0 top-0 h-full text-blue-800 opacity-20 transform translate-x-1/4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 2H5C3.9 2 3 2.9 3 4v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 4h2v5l-1-.75L9 9V4zm11 15H4V4h4v8l3-2.25L14 12V4h5v15z"></path>
                        </svg>
                        
                        <div class="relative z-10">
                            <span class="inline-block bg-logo-yellow text-blue-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-4">
                                Liturgi Minggu Ini
                            </span>
                            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">
                                {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
                            </h2>
                            <p class="text-blue-100 text-lg leading-relaxed mb-8">
                                Persiapkan hati untuk merayakan misteri iman. Informasi kalender liturgi Harian.
                            </p>
                            <a href="http://calapi.inadiutorium.cz/" target="_blank" class="inline-flex items-center px-6 py-3 bg-white text-logo-blue font-bold rounded-lg shadow-md hover:bg-logo-yellow hover:text-blue-900 transition-all duration-300">
                                Lihat Sumber Data
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Kanan: Komponen Liturgi Dinamis dari API -->
                    <div class="p-6 md:p-10 flex items-center justify-center bg-white">
                        
                        <!-- Kartu Informasi Liturgi -->
                        <div class="w-full bg-gray-50 rounded-2xl p-8 shadow-inner border border-gray-200 space-y-6">
                            
                            <!-- Bagian Judul dan Warna -->
                            <div class="text-center">
                                @php
                                    // Logika untuk menentukan warna badge berdasarkan data dari API
                                    $warna = $liturgi['warna'] ?? 'Hijau';
                                    $badgeClass = match (strtolower($warna)) {
                                        'putih' => 'bg-gray-200 text-gray-800 border-2 border-gray-300',
                                        'merah' => 'bg-red-600 text-white',
                                        'ungu' => 'bg-purple-600 text-white',
                                        'merah muda' => 'bg-pink-400 text-white',
                                        default => 'bg-green-600 text-white', // Hijau dan default
                                    };
                                @endphp
                                
                                <div class="flex justify-center items-center gap-3 mb-3">
                                    <h3 class="font-bold text-gray-500 text-sm uppercase tracking-wider">Warna Liturgi:</h3>
                                    <span class="px-4 py-1 rounded-full text-sm font-bold shadow-sm {{ $badgeClass }}">
                                        {{ $warna }}
                                    </span>
                                </div>
                                
                                <h2 class="text-2xl font-extrabold text-gray-800 leading-tight">
                                    {{ $liturgi['perayaan'] ?? 'Informasi Tidak Tersedia' }}
                                </h2>
                            </div>

                            <!-- Garis Pemisah -->
                            <div class="border-t border-gray-200"></div>

                            <!-- Bagian Bacaan -->
                            <div>
                                <h4 class="font-bold text-gray-600 mb-3 text-center">Bacaan Harian</h4>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex justify-between items-center bg-white p-3 rounded-lg border">
                                        <span class="font-semibold text-gray-500">Bacaan I:</span>
                                        <span class="font-mono text-gray-800">{{ $liturgi['bacaan_1'] ?? '-' }}</span>
                                    </li>
                                    <li class="flex justify-between items-center bg-white p-3 rounded-lg border">
                                        <span class="font-semibold text-gray-500">Mazmur:</span>
                                        <span class="font-mono text-gray-800">{{ $liturgi['mazmur'] ?? '-' }}</span>
                                    </li>
                                    <li class="flex justify-between items-center bg-white p-3 rounded-lg border">
                                        <span class="font-semibold text-gray-500">Injil:</span>
                                        <span class="font-mono text-gray-800">{{ $liturgi['injil'] ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection