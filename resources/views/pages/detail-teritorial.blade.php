@extends('layouts.main')

@section('title', 'Wilayah ' . $territory->name . ' - Gereja St. Ignatius Loyola')
@section('header', '')
<meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik, gereja di Sleman, Jadwal Misa, Pengumuman Gereja, Teritorial Wilayah, Organisasi Gereja, Petugas Liturgi, OMK, Misdinar, Lektor, Mazmur, Paduan Suara, Parkir Gereja, kalasan tengah, gereja yogyakarta, 
    gereja sleman, gereja di kalasan, paroki maria marganingsih kalasan, Gereja St. Ignatius Loyola Temanggal, Gereja Katolik di Kalasan, Jadwal Misa Kalasan, Pengumuman Gereja Kalasan, Teritorial Wilayah Kalasan, Organisasi Gereja Kalasan, Petugas Liturgi Kalasan, OMK Kalasan, Misdinar Kalasan, Lektor Kalasan, Mazmur Kalasan, Paduan Suara Kalasan, Parkir Gereja Kalasan,
    gereja temanggal, gereja di temanggal, paroki kalasan, Gereja St. Ignatius Loyola Kalasan Tengah Temanggal ')">

@section('content')
<div class="min-h-screen bg-gray-50 pb-12">
    
    <!-- HEADER -->
    <div class="bg-logo-blue py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="text-blue-200 font-bold tracking-widest uppercase text-sm mb-2 block">Profil Wilayah</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">
                {{ $territory->name }}
            </h1>
            <p class="text-white/80 max-w-2xl mx-auto text-lg">
                {{ $territory->description }}
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        
        <!-- INTRO CARD -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-10 border-l-8 border-logo-red flex items-start">
            <div class="bg-red-50 p-3 rounded-full mr-4 text-logo-red hidden md:block">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Daftar Lingkungan</h2>
                <p class="text-gray-600">
                    Wilayah ini menaungi <strong>{{ $territory->lingkungans->count() }} Lingkungan</strong>. Berikut adalah detail informasi setiap lingkungan.
                </p>
            </div>
        </div>

        <!-- GRID LINGKUNGAN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($territory->lingkungans as $lingkungan)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden group">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center group-hover:bg-blue-50 transition">
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-logo-blue">
                        {{ $lingkungan->name }}
                    </h3>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-logo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <!-- Info Ketua (Jika ada di database nanti) -->
                        <div class="flex items-start">
                            <span class="text-xs font-bold text-gray-400 uppercase w-24 pt-1">Informasi:</span>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ $lingkungan->info ?? 'Belum ada informasi detail mengenai jadwal atau kegiatan spesifik untuk lingkungan ini.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi (Opsional) -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <span class="text-xs font-semibold text-logo-blue bg-blue-50 px-2 py-1 rounded">
                            Lingkungan Aktif
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="/teritorial" class="text-gray-500 hover:text-logo-blue font-medium transition">
                ← Kembali ke Peta Utama
            </a>
        </div>

    </div>
</div>
@endsection