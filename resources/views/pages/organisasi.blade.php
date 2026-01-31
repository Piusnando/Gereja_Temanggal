@extends('layouts.main')

@section('title', 'Struktur Organisasi - Gereja St. Ignatius Loyola')
<meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik, gereja di Sleman, Jadwal Misa, Pengumuman Gereja, Teritorial Wilayah, Organisasi Gereja, Petugas Liturgi, OMK, Misdinar, Lektor, Mazmur, Paduan Suara, Parkir Gereja, kalasan tengah, gereja yogyakarta, 
    gereja sleman, gereja di kalasan, paroki maria marganingsih kalasan, Gereja St. Ignatius Loyola Temanggal, Gereja Katolik di Kalasan, Jadwal Misa Kalasan, Pengumuman Gereja Kalasan, Teritorial Wilayah Kalasan, Organisasi Gereja Kalasan, Petugas Liturgi Kalasan, OMK Kalasan, Misdinar Kalasan, Lektor Kalasan, Mazmur Kalasan, Paduan Suara Kalasan, Parkir Gereja Kalasan,
    gereja temanggal, gereja di temanggal, paroki kalasan, Gereja St. Ignatius Loyola Kalasan Tengah Temanggal ')">

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">
    
    <!-- HEADER UTAMA -->
    <div class="bg-logo-blue py-16 relative overflow-hidden mb-10 shadow-lg">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white uppercase tracking-wide drop-shadow-md">
                Struktur Organisasi
            </h1>
            <p class="text-blue-100 mt-3 text-lg font-light">
                Pengurus Gereja, Tim Pelayanan, dan Kelompok Kategorial
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        @foreach($categoriesOrder as $category)
            @php
                // Ambil anggota untuk kategori ini dari grup
                $members = $groupedMembers->get($category);
            @endphp

            {{-- Hanya tampilkan jika kategori tersebut memiliki anggota --}}
            @if($members && $members->count() > 0)
            
            <section>
                <!-- JUDUL KATEGORI (Mirip Admin Header) -->
                <div class="flex items-center mb-6">
                    <div class="w-1.5 h-8 bg-logo-red rounded-full mr-3"></div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 uppercase tracking-tight">
                        {{ $category }}
                    </h2>
                    <div class="ml-4 grow h-px bg-gray-200"></div> <!-- Garis pemisah -->
                </div>

                <!-- GRID ANGGOTA -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($members as $member)
                    <div class="flex items-center p-5 border border-gray-100 rounded-xl hover:shadow-xl transition-all duration-300 bg-white group transform hover:-translate-y-1">
                        
                        <!-- FOTO PROFIL -->
                        <div class="w-16 h-16 rounded-full overflow-hidden mr-4 border-2 border-gray-100 shadow-sm shrink-0 group-hover:border-logo-blue transition duration-300">
                            @if($member->image)
                                <img src="{{ asset('storage/' . $member->image) }}" 
                                     class="w-full h-full object-cover" 
                                     alt="{{ $member->name }}">
                            @else
                                <!-- Inisial Default -->
                                <div class="w-full h-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xl uppercase group-hover:bg-blue-50 group-hover:text-logo-blue transition">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- DETAIL -->
                        <div class="overflow-hidden">
                            <h3 class="font-bold text-gray-900 text-lg leading-tight truncate group-hover:text-logo-blue transition">
                                {{ $member->name }}
                            </h3>
                            
                            <div class="mt-1">
                                <span class="inline-block bg-logo-red text-white text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wider shadow-sm">
                                    {{ $member->position }}
                                </span>
                            </div>
                            
                            @if($member->lingkungan)
                            <p class="text-xs text-gray-500 flex items-center mt-1.5 truncate">
                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $member->lingkungan->name }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

        @endforeach

        {{-- Jika Semua Kosong --}}
        @if($groupedMembers->isEmpty())
            <div class="text-center py-20 bg-white rounded-xl shadow border border-gray-100">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <p class="text-lg text-gray-500 font-medium">Belum ada data struktur organisasi.</p>
            </div>
        @endif

    </div>
</div>
@endsection