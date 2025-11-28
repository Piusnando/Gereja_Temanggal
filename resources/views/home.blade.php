@extends('layouts.main')

@section('title', 'Beranda - Gereja St. Ignatius Loyola Temanggal')
@section('header', '')

@section('content')

    {{-- SECTION 1: HERO BANNER (SLIDER) --}}
    <!-- Tambahkan class 'mb-12' untuk memberi jarak ke bawah -->
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
            next() {
                this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1;
                this.resetTimer();
            },
            prev() {
                this.activeSlide = this.activeSlide === 1 ? this.slides.length : this.activeSlide - 1;
                this.resetTimer();
            },
            resetTimer() {
                clearInterval(this.interval);
                this.interval = setInterval(() => this.loop(), 5000);
            },
            interval: null
        }" 
        x-init="resetTimer()"
        class="relative w-full h-[400px] md:h-[600px] overflow-hidden rounded-b-[3rem] shadow-2xl group border-b-8 border-logo-red mb-12"> <!-- mb-12 adalah kuncinya -->
        
        <!-- Jika tidak ada banner -->
        @if($banners->isEmpty())
            <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-500">
                Belum ada banner yang diupload.
            </div>
        @endif

        <!-- Loop Images -->
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index + 1"
                 x-transition:enter="transition transform duration-1000 ease-in-out"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition transform duration-1000 ease-in-out"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 bg-cover bg-center w-full h-full"
                 :style="`background-image: url('${slide}')`">
                 <div class="absolute inset-0 bg-black/40"></div>
            </div>
        </template>

        <!-- Teks Tengah -->
        <div class="absolute inset-0 flex items-center justify-center text-center px-4 z-10 pointer-events-none">
            <div class="max-w-4xl drop-shadow-lg pointer-events-auto mt-10">
                <h2 class="text-sm md:text-xl font-bold uppercase tracking-[0.2em] mb-2 text-logo-yellow">Selamat Datang di Website Resmi</h2>
                <h1 class="text-3xl md:text-6xl font-extrabold leading-tight mb-4 text-white drop-shadow-md">
                    Gereja St. Ignatius Loyola<br>
                    <span class="text-red-500">Kalasan Tengah</span>
                </h1>
                <p class="text-sm md:text-lg font-medium text-gray-200 mt-2 bg-black/30 inline-block px-4 py-1 rounded-full backdrop-blur-sm">
                    Paroki Maria Marganingsih Kalasan
                </p>
                <div class="mt-8">
                    <a href="#jadwal" class="bg-logo-red hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full transition shadow-lg border-2 border-transparent hover:border-white hover:scale-105 transform duration-300">
                        Lihat Jadwal Misa
                    </a>
                </div>
            </div>
        </div>

        <!-- Tombol Navigasi -->
        <button @click="prev()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-logo-red text-white p-3 rounded-full transition duration-300 z-20 focus:outline-none group-hover:bg-logo-red backdrop-blur-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button @click="next()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-logo-red text-white p-3 rounded-full transition duration-300 z-20 focus:outline-none group-hover:bg-logo-red backdrop-blur-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>


    {{-- SECTION 2: PENGUMUMAN TERBARU --}}
    <!-- Hapus class '-mt-10' dan 'relative z-10' agar tidak naik ke atas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Pengumuman Terbaru</h2>
                <p class="text-gray-500 mt-1">Informasi kegiatan, liturgi, dan berita paroki.</p>
            </div>
            <a href="/pengumuman" class="mt-4 md:mt-0 inline-flex items-center text-logo-blue hover:text-blue-800 font-bold transition">
                Lihat Arsip
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <!-- Grid Cards (Sama seperti sebelumnya) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($announcements as $item)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 border border-gray-100 flex flex-col h-full">
                
                <!-- BAGIAN GAMBAR -->
                <div class="h-52 w-full bg-gray-200 relative overflow-hidden group">
                    <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/500x300?text=Gereja+Temanggal' }}" 
                         alt="{{ $item->title }}" 
                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    
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
                    <div class="absolute top-4 left-4 {{ $badgeColor }} text-white text-[10px] uppercase font-bold px-3 py-1 rounded-full shadow-md tracking-wider">
                        {{ $item->category }}
                    </div>
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
                    
                    <a href="{{ route('pengumuman.detail', $item->id) }}" class="inline-flex items-center text-sm font-bold text-logo-red hover:text-red-800 mt-auto transition">
                        Baca Selengkapnya
                        <svg ... >...</svg>
                    </a>
                </div>
            </div>
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


    {{-- SECTION 3: LOKASI & JADWAL (Fixed Layout) --}}
    <div id="jadwal" class="py-16 bg-linear-to-b from-gray-50 to-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <!-- KOLOM KIRI: Peta Google Maps -->
                <div class="flex flex-col h-full">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="bg-red-100 text-red-600 p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        </span>
                        Lokasi Gereja
                    </h2>
                    
                    <div class="bg-white p-2 rounded-2xl shadow-lg border border-gray-200 grow min-h-[400px]">
                        <iframe 
                            src="https://maps.google.com/maps?q=Gereja+Katolik+Santo+Ignatius+Loyola+Temanggal+Sleman+Yogyakarta&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="100%" 
                            style="border:0; border-radius: 1rem;" 
                            allowfullscreen="" 
                            loading="lazy"
                            class="shadow-inner"
                            title="Peta Lokasi Gereja">
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

                <!-- KOLOM KANAN: Jadwal & Info -->
                <div class="flex flex-col space-y-8">
                    
                    <!-- Box Jadwal Misa -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border-t-8 border-logo-red relative overflow-hidden group">
                        <!-- Decorative Background -->
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-red-50 rounded-full opacity-50 transition group-hover:scale-110"></div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center relative z-10">
                            <span class="bg-red-100 text-logo-red p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Jadwal Misa
                        </h2>
                        
                        <div class="space-y-4 relative z-10">
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
                            Umat dimohon hadir 15 menit sebelum misa dimulai.
                        </div>
                    </div>

                    <!-- Box Teritorial -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border-t-8 border-logo-blue flex flex-col relative overflow-hidden h-full">
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
                            <!-- Loop Data Wilayah dari Database -->
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
@endsection