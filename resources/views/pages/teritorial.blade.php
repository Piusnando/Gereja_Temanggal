@extends('layouts.main')

@section('title', 'Peta Teritorial - Gereja St. Ignatius Loyola')
@section('header', '')

<meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik, gereja di Sleman, Jadwal Misa, Pengumuman Gereja, Teritorial Wilayah, Organisasi Gereja, Petugas Liturgi, OMK, Misdinar, Lektor, Mazmur, Paduan Suara, Parkir Gereja, kalasan tengah, gereja yogyakarta, 
    gereja sleman, gereja di kalasan, paroki maria marganingsih kalasan, Gereja St. Ignatius Loyola Temanggal, Gereja Katolik di Kalasan, Jadwal Misa Kalasan, Pengumuman Gereja Kalasan, Teritorial Wilayah Kalasan, Organisasi Gereja Kalasan, Petugas Liturgi Kalasan, OMK Kalasan, Misdinar Kalasan, Lektor Kalasan, Mazmur Kalasan, Paduan Suara Kalasan, Parkir Gereja Kalasan,
    gereja temanggal, gereja di temanggal, paroki kalasan, Gereja St. Ignatius Loyola Kalasan Tengah Temanggal ')">

@section('content')
<div class="min-h-screen bg-gray-50 pb-12">
    
    <!-- HEADER SECTION -->
    <div class="bg-logo-blue pt-16 pb-24 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/black-scales.png')]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Peta Wilayah & Lingkungan
            </h1>
            <p class="text-blue-100 text-lg md:text-xl font-medium max-w-3xl mx-auto leading-relaxed">
                Pembagian administratif Kalasan Tengah Paroki Maria Marganingsih Kalasan untuk pelayanan umat yang lebih dekat dan efektif.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        
        <!-- STATISTIK CARD -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Box Total Wilayah -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center border-l-4 border-logo-red transform hover:-translate-y-1 transition duration-300">
                <div class="bg-red-50 p-4 rounded-full mr-4 text-logo-red">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total Wilayah</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $territories->count() }}</h3>
                </div>
            </div>

            <!-- Box Total Lingkungan -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center border-l-4 border-logo-blue transform hover:-translate-y-1 transition duration-300">
                <div class="bg-blue-50 p-4 rounded-full mr-4 text-logo-blue">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total Lingkungan</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $totalLingkungan }}</h3>
                </div>
            </div>

            <!-- Box Pusat Paroki -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center border-l-4 border-logo-yellow transform hover:-translate-y-1 transition duration-300">
                <div class="bg-yellow-50 p-4 rounded-full mr-4 text-logo-yellow">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Pusat Paroki</p>
                    <h3 class="text-xl font-black text-gray-800">Kalasan</h3>
                </div>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- DIREKTORI WILAYAH (POSISI DIATAS) -->
        <!-- ============================================= -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-logo-blue pl-3">
            Direktori Wilayah & Lingkungan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            @foreach($territories as $wilayah)
            
            <!-- UPDATE: GANTI DIV MENJADI A AGAR BISA DIKLIK -->
            <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-300 border border-gray-100 flex flex-col h-full group relative overflow-hidden">
                
                <!-- Header Card -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center transition-colors duration-300 group-hover:bg-[#003399]">
                    <h3 class="text-lg font-bold text-gray-800 transition-colors duration-300 group-hover:text-white">
                        {{ $wilayah->name }}
                    </h3>
                    <!-- Icon Panah (Berubah warna saat hover) -->
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>

                <!-- Body Card -->
                <div class="p-6 grow">
                    <p class="text-sm text-gray-500 mb-4 italic">
                        {{ $wilayah->description ?? 'Wilayah pelayanan pastoral.' }}
                    </p>
                    
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Daftar Lingkungan:</h4>
                    <ul class="grid grid-cols-1 gap-2">
                        @foreach($wilayah->lingkungans->take(5) as $lingkungan) <!-- Limit tampil 5 saja agar tidak terlalu panjang -->
                            <li class="flex items-start text-sm text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $lingkungan->name }}
                            </li>
                        @endforeach
                        @if($wilayah->lingkungans->count() > 5)
                            <li class="text-xs text-blue-500 italic ml-6">+ {{ $wilayah->lingkungans->count() - 5 }} Lingkungan lainnya</li>
                        @endif
                    </ul>
                </div>

                <!-- Footer Card -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 mt-auto text-center transition-colors duration-300 group-hover:bg-blue-50">
                    <span class="inline-block w-full text-sm font-bold text-logo-blue group-hover:text-logo-red transition">
                        Lihat Detail Profil Wilayah →
                    </span>
                </div>
            </a>
            @endforeach
        </div>


        <!-- ============================================= -->
        <!-- AREA PETA BESAR (POSISI DIBAWAH) -->
        <!-- ============================================= -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-12 border border-gray-200">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-logo-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-1.447-.894L15 7m0 13V7"></path></svg>
                    Peta Persebaran Wilayah
                </h2>
            </div>
            
            <div class="w-full h-[600px] relative bg-gray-200">
                <!-- 
                   Google My Maps Embed
                -->
                <iframe 
                    src="https://www.google.com/maps/d/u/0/embed?mid=1qL9-zchgfJVcgRiEtkLojOYuuQzdpYg&ehbc=2E312F" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>

    </div>
</div>
@endsection