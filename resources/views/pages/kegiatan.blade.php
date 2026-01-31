@extends('layouts.main')

@section('title', 'Info Kegiatan - Gereja St. Ignatius Loyola')
@section('header', '')
<meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik, gereja di Sleman, Jadwal Misa, Pengumuman Gereja, Teritorial Wilayah, Organisasi Gereja, Petugas Liturgi, OMK, Misdinar, Lektor, Mazmur, Paduan Suara, Parkir Gereja, kalasan tengah, gereja yogyakarta, 
    gereja sleman, gereja di kalasan, paroki maria marganingsih kalasan, Gereja St. Ignatius Loyola Temanggal, Gereja Katolik di Kalasan, Jadwal Misa Kalasan, Pengumuman Gereja Kalasan, Teritorial Wilayah Kalasan, Organisasi Gereja Kalasan, Petugas Liturgi Kalasan, OMK Kalasan, Misdinar Kalasan, Lektor Kalasan, Mazmur Kalasan, Paduan Suara Kalasan, Parkir Gereja Kalasan,
    gereja temanggal, gereja di temanggal, paroki kalasan, Gereja St. Ignatius Loyola Kalasan Tengah Temanggal ')">

@section('content')
<div class="min-h-screen bg-gray-50 pb-16">
    
    <!-- HEADER -->
    <div class="bg-logo-blue pt-16 pb-24 relative overflow-hidden shadow-lg">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/black-scales.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Info & Berita Kegiatan
            </h1>
            <p class="text-blue-100 text-lg md:text-xl font-medium max-w-3xl mx-auto leading-relaxed">
                Dokumentasi, warta, dan agenda kegiatan umat Ignatius Temanggal.
            </p>
        </div>
    </div>

    <!-- KONTEN -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        
        <!-- Search Bar -->
        <div class="bg-white p-6 rounded-2xl shadow-xl mb-10 border border-gray-100">
            <form action="{{ route('kegiatan.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-logo-blue transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $currentSearch ?? '' }}" 
                       class="w-full border border-gray-300 rounded-xl py-3 pl-11 pr-24 focus:outline-none focus:ring-2 focus:ring-logo-blue transition"
                       placeholder="Cari kegiatan, penyelenggara...">
                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-logo-blue text-white px-6 rounded-lg font-bold hover:bg-blue-800 transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Grid Kegiatan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($activities as $item)
            <a href="{{ route('kegiatan.detail', $item->id) }}" class="group block bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-300 border border-gray-100 overflow-hidden flex-col h-full">
                
                <!-- Gambar -->
                <div class="h-52 overflow-hidden relative bg-gray-200">
                    <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://placehold.co/600x400/png?text=Kegiatan+Paroki' }}" 
                         alt="{{ $item->title }}" 
                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    
                    <!-- Badge Tanggal -->
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-gray-800 px-3 py-1 rounded-lg shadow-sm text-xs font-bold uppercase tracking-wider flex flex-col items-center border border-gray-200">
                        <span class="text-xl leading-none text-logo-blue">{{ $item->start_time->format('d') }}</span>
                        <span class="text-[10px]">{{ $item->start_time->format('M') }}</span>
                    </div>

                    <!-- Badge Status (Selesai/Akan Datang) -->
                    @if($item->start_time > now())
                        <div class="absolute top-4 right-4 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">
                            AKAN DATANG
                        </div>
                    @else
                        <div class="absolute top-4 right-4 bg-gray-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow">
                            SELESAI
                        </div>
                    @endif
                </div>

                <!-- Konten -->
                <div class="p-6 flex flex-col grow">
                    <div class="flex items-center text-xs text-gray-500 mb-2 font-semibold uppercase">
                        <span class="text-logo-red mr-2">{{ $item->organizer }}</span>
                        &bull;
                        <span class="ml-2 truncate">{{ $item->location }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-logo-blue transition line-clamp-2">
                        {{ $item->title }}
                    </h3>

                    <p class="text-gray-600 text-sm line-clamp-3 mb-4 grow">
                        {{ Str::limit(strip_tags($item->description), 120) }}
                    </p>

                    <div class="pt-4 border-t border-gray-100 flex items-center text-logo-blue font-bold text-sm">
                        Lihat Detail
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-1 md:col-span-3 text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <h3 class="text-lg font-medium text-gray-900">Tidak ada kegiatan ditemukan</h3>
                <p class="text-gray-500 mt-1">Belum ada data kegiatan atau pencarian tidak cocok.</p>
                @if($currentSearch)
                    <a href="{{ route('kegiatan.index') }}" class="inline-block mt-4 text-logo-blue font-bold hover:underline">Reset Pencarian</a>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $activities->appends(['search' => $currentSearch])->links() }}
        </div>

    </div>
</div>
@endsection