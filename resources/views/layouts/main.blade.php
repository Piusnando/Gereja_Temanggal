<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ $globalLogo ?? asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $globalLogo ?? asset('favicon.ico') }}" type="image/x-icon">

    <!-- ================= SEO TAGS ================= -->
    <title>@yield('title', 'Gereja St. Ignatius Loyola Kalasan Tengah')</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik, gereja di Sleman, Jadwal Misa, Pengumuman Gereja, Teritorial Wilayah, Organisasi Gereja, Petugas Liturgi, OMK, Misdinar, Lektor, Mazmur, Paduan Suara, Parkir Gereja, kalasan tengah, gereja yogyakarta, 
    gereja sleman, gereja di kalasan, paroki maria marganingsih kalasan, Gereja St. Ignatius Loyola Temanggal, Gereja Katolik di Kalasan, Jadwal Misa Kalasan, Pengumuman Gereja Kalasan, Teritorial Wilayah Kalasan, Organisasi Gereja Kalasan, Petugas Liturgi Kalasan, OMK Kalasan, Misdinar Kalasan, Lektor Kalasan, Mazmur Kalasan, Paduan Suara Kalasan, Parkir Gereja Kalasan,
    gereja temanggal, gereja di temanggal, paroki kalasan, Gereja St. Ignatius Loyola Kalasan Tengah Temanggal ')">
    <meta name="author" content="Gereja St. Ignatius Loyola">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical Link -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph (Social Media SEO) -->
    <meta property="og:title" content="@yield('title', 'Gereja St. Ignatius Loyola Kalasan Tengah')">
    <meta property="og:description" content="@yield('meta_description', 'Informasi resmi seputar kegiatan dan pelayanan Gereja St. Ignatius Loyola Kalasan Tengah.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo-default.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <!-- ============================================ -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'logo-blue': '#003399',
                        'logo-red': '#DC2626',
                        'logo-yellow': '#FFCC00',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-dropdown { z-index: 9999 !important; }
        [x-cloak] { display: none !important; }
        
        .popup-overlay { opacity: 0; visibility: hidden; transition: all 0.3s ease-in-out; z-index: 9999 !important; }
        .popup-overlay.active { opacity: 1; visibility: visible; }
        .popup-content { transform: scale(0.95); opacity: 0; transition: all 0.3s ease-out; }
        .popup-overlay.active .popup-content { transform: scale(1); opacity: 1; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 relative z-50 shadow-sm" x-data="{ open: false }">
        
        <!-- CONTAINER: Kembali menggunakan max-w-7xl sesuai request -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 lg:h-24"> 

                <!-- 1. LOGO & NAMA GEREJA (KIRI) -->
                <!-- shrink-0 agar logo tidak tergencet -->
                <div class="flex items-center gap-2 sm:gap-4 shrink-0 overflow-hidden">
                    
                    <!-- LOGO: Ukuran h-10 di HP, h-14 di Desktop -->
                    <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" 
                         alt="Logo Gereja" 
                         class="h-9 sm:h-10 lg:h-14 w-auto object-contain shrink-0">
                    
                    <div class="flex flex-col justify-center">
                        <!-- JUDUL: Ukuran text-xs di HP, text-lg di Desktop -->
                        <a href="/" class="font-extrabold text-logo-blue leading-tight uppercase tracking-wide transition hover:opacity-80">
                            <!-- Baris 1 -->
                            <span class="text-xs sm:text-sm lg:text-xl block">
                                Gereja St. Ignatius Loyola
                            </span>
                            <!-- Baris 2 -->
                            <span class="text-logo-red text-xs sm:text-sm lg:text-xl block">
                                Temanggal
                            </span>
                        </a>
                        
                        <!-- SUB-JUDUL: Sembunyikan di HP yang sangat kecil, Muncul di Tablet/Desktop -->
                        <span class="text-[8px] lg:text-[10px] text-gray-500 font-semibold tracking-widest mt-0.5 uppercase">
                            Paroki Maria Marganingsih Kalasan
                        </span>
                    </div>
                </div>

                <!-- 2. MENU DESKTOP (KANAN) -->
                <!-- 
                   PERBAIKAN DISINI:
                   - lg:gap-x-4 : Jarak diperkecil di laptop agar muat
                   - xl:gap-x-8 : Jarak lebar di monitor besar
                   - lg:text-xs : Font kecil di laptop
                   - xl:text-sm : Font normal di monitor besar
                   - lg:ml-10   : Jarak Margin Kiri agar tidak nempel logo
                -->
                <div class="hidden lg:flex lg:items-center lg:gap-x-4 xl:gap-x-8 lg:text-xs xl:text-sm font-bold tracking-wider lg:ml-10">
                    
                    <!-- MENU: BERANDA -->
                    <a href="/" class="whitespace-nowrap h-24 flex items-center uppercase border-b-4 transition-all duration-300 {{ request()->path() === '/' ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Beranda
                    </a>

                    <!-- MENU: SEJARAH -->
                    <a href="/sejarah" class="whitespace-nowrap h-24 flex items-center uppercase border-b-4 transition-all duration-300 {{ request()->is('sejarah*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Sejarah
                    </a>

                    <!-- MENU DROPDOWN: INFO KEGIATAN -->
                    <div class="relative h-24 flex items-center group" x-data="{ infoOpen: false }" @click.away="infoOpen = false">
                        <button @click="infoOpen = ! infoOpen" class="whitespace-nowrap h-full flex items-center uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('pengumuman*') || request()->is('kegiatan*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue' }}">
                            Info Kegiatan
                            <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': infoOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="infoOpen" x-transition x-cloak class="nav-dropdown absolute top-[85%] right-0 w-56 bg-white shadow-xl rounded-xl border border-gray-100 py-2">
                            <a href="/pengumuman" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 border-b border-gray-50">Arsip Pengumuman</a>
                            <a href="{{ route('kegiatan.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 border-b border-gray-50">Kegiatan</a>
                            <a href="{{ route('jadwal.gedung') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50">Jadwal Gedung</a>
                        </div>
                    </div>

                    <!-- MENU DROPDOWN: TERITORIAL -->
                    <div class="relative h-24 flex items-center group" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                        <button @click="dropdownOpen = ! dropdownOpen" class="whitespace-nowrap h-full flex items-center uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('teritorial*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Teritorial <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': dropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="dropdownOpen" x-transition x-cloak class="nav-dropdown absolute top-[80%] right-0 w-64 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                             <div class="py-2">
                                 @if(isset($globalTerritories))
                                     @foreach($globalTerritories as $wilayah)
                                        <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block px-4 py-3 text-sm font-medium border-b border-gray-50 last:border-0 hover:bg-blue-50 hover:text-logo-blue">{{ $wilayah->name }}</a>
                                     @endforeach
                                 @endif
                                 <a href="/teritorial" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase hover:bg-blue-800">Lihat Peta Besar</a>
                             </div>
                        </div>
                    </div>

                    <!-- MENU DROPDOWN: ORGANISASI -->
                    <div class="relative h-24 flex items-center" x-data="{ orgOpen: false, activeSub: null }" @click.away="orgOpen = false; activeSub = null">
                        <!-- Perhatikan whitespace-nowrap di sini -->
                        <button @click="orgOpen = ! orgOpen" class="whitespace-nowrap h-full flex items-center uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('organisasi*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Pengurus Gereja <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': orgOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <!-- Include Logic Dropdown Organisasi (Vertical Accordion) yang tadi -->
                        <div x-show="orgOpen" x-transition x-cloak class="nav-dropdown absolute top-[80%] right-0 w-80 bg-white shadow-xl rounded-xl border border-gray-100 py-2 max-h-[80vh] overflow-y-auto">
                            @if(isset($organizationMenu))
                                @foreach($organizationMenu as $bidang => $subs)
                                    <div class="border-b border-gray-50 last:border-0">
                                        <button 
                                            @click="activeSub === '{{ $bidang }}' ? activeSub = null : activeSub = '{{ $bidang }}'"
                                            class="flex justify-between items-center w-full text-left px-5 py-3 text-sm font-medium transition hover:bg-gray-50 focus:outline-none"
                                            :class="activeSub === '{{ $bidang }}' ? 'text-logo-blue bg-blue-50' : 'text-gray-700'"
                                        >
                                            <span class="flex-1 whitespace-normal leading-snug pr-2">{{ $bidang }}</span>
                                            @if(count($subs) > 0)
                                                <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="activeSub === '{{ $bidang }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            @endif
                                        </button>
                                        @if(count($subs) > 0)
                                            <div x-show="activeSub === '{{ $bidang }}'" x-collapse class="bg-gray-100 border-t border-gray-100">
                                                <a href="{{ route('organisasi.show', ['category' => $bidang]) }}" class="block px-8 py-2 text-xs font-bold text-logo-blue uppercase tracking-wide hover:bg-gray-200 border-b border-gray-200">Buka Laman Utama</a>
                                                @foreach($subs as $sub)
                                                    <a href="{{ route('organisasi.sub', ['category' => $bidang, 'sub_category' => $sub]) }}" class="block px-8 py-2 text-sm text-gray-600 hover:text-red-600 hover:bg-gray-200 transition border-b border-gray-200 last:border-0 items-start">
                                                        <span class="text-red-400 mr-2 mt-0.5">•</span><span class="whitespace-normal leading-snug">{{ $sub }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div x-init="$watch('activeSub', value => { if(value === '{{ $bidang }}') window.location.href='{{ route('organisasi.show', ['category' => $bidang]) }}' })"></div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- MENU DROPDOWN: PETUGAS LITURGI -->
                    <div class="relative h-24 flex items-center group" x-data="{ liturgiOpen: false }" @click.away="liturgiOpen = false">
                        <button @click="liturgiOpen = ! liturgiOpen" class="whitespace-nowrap h-full flex items-center uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('petugas*') || request()->is('jadwal-petugas') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Petugas Liturgi <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': liturgiOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="liturgiOpen" x-transition x-cloak class="nav-dropdown absolute top-[80%] right-0 w-48 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                             <div class="py-2">
                                 @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                                 <a href="{{ route('petugas.role', ['role' => $tugas]) }}" class="block px-4 py-3 text-sm font-medium border-b border-gray-50 last:border-0 hover:bg-blue-50 hover:text-logo-blue">{{ $tugas }}</a>
                                 @endforeach
                                 <a href="/jadwal-petugas" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase hover:bg-blue-800">Lihat Semua Jadwal</a>
                             </div>
                        </div>
                    </div>

                </div>

                <!-- 3. TOMBOL HAMBURGER (MOBILE MENU) -->
                <div class="-mr-2 flex items-center lg:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-logo-blue">
                        <span class="sr-only">Buka menu utama</span>
                        <!-- Icon Menu (Hamburger) -->
                        <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU (RESPONSIVE) -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-xl absolute w-full left-0 z-50 overflow-y-auto max-h-[85vh]">
            <div class="py-2 pb-6 space-y-1">
                
                <!-- 1. BERANDA -->
                <a href="/" class="block px-6 py-3 border-l-4 text-sm font-bold uppercase transition {{ request()->path() === '/' ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Beranda
                </a>

                <!-- 2. SEJARAH -->
                <a href="/sejarah" class="block px-6 py-3 border-l-4 text-sm font-bold uppercase transition {{ request()->is('sejarah*') ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Sejarah
                </a>

                <!-- 3. INFO KEGIATAN (Dropdown) -->
                <div x-data="{ expanded: {{ request()->is('pengumuman*') || request()->is('kegiatan*') || request()->routeIs('jadwal.gedung') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" 
                            class="w-full flex justify-between items-center px-6 py-3 border-l-4 text-sm font-bold uppercase transition focus:outline-none 
                            {{ request()->is('pengumuman*') || request()->is('kegiatan*') || request()->routeIs('jadwal.gedung') ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50' }}">
                        <span>Info Kegiatan</span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="expanded" x-collapse class="bg-gray-50 border-t border-gray-100">
                        <a href="/pengumuman" class="block pl-10 pr-4 py-3 text-xs font-bold text-gray-600 hover:text-logo-blue border-b border-gray-200">Arsip Pengumuman</a>
                        <a href="{{ route('kegiatan.index') }}" class="block pl-10 pr-4 py-3 text-xs font-bold text-gray-600 hover:text-logo-blue border-b border-gray-200">Kegiatan</a>
                        <a href="{{ route('jadwal.gedung') }}" class="block pl-10 pr-4 py-3 text-xs font-bold text-gray-600 hover:text-logo-blue">Jadwal Gedung</a>
                    </div>
                </div>

                <!-- 4. TERITORIAL (Dropdown) -->
                <div x-data="{ expanded: {{ request()->is('teritorial*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-sm font-bold uppercase text-gray-700 hover:bg-gray-50 focus:outline-none {{ request()->is('teritorial*') ? 'text-logo-blue' : '' }}">
                        <span>Teritorial</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" x-collapse class="bg-gray-50 border-t border-gray-100">
                        @if(isset($globalTerritories))
                            @foreach($globalTerritories as $wilayah)
                            <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block pl-10 pr-4 py-3 text-xs font-bold text-gray-600 hover:text-logo-blue border-b border-gray-200">
                                {{ $wilayah->name }}
                            </a>
                            @endforeach
                        @endif
                        <a href="/teritorial" class="block pl-10 pr-4 py-3 text-xs font-bold text-white bg-logo-blue text-center">LIHAT PETA BESAR</a>
                    </div>
                </div>

                <!-- 5. ORGANISASI (Nested Accordion - RAPID & BERTINGKAT) -->
                <div x-data="{ 
                    expanded: {{ request()->is('organisasi*') ? 'true' : 'false' }}, 
                    activeSubMobile: null 
                }">
                    <!-- Level 1: Tombol Utama -->
                    <button @click="expanded = !expanded" class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-sm font-bold uppercase text-gray-700 hover:bg-gray-50 focus:outline-none {{ request()->is('organisasi*') ? 'text-logo-blue' : '' }}">
                        <span>Pengurus Gereja</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Level 2: Daftar Bidang -->
                    <div x-show="expanded" x-collapse class="bg-gray-50 border-t border-gray-100">
                        @if(isset($organizationMenu))
                            @foreach($organizationMenu as $bidang => $subs)
                                <div class="border-b border-gray-200 last:border-0">
                                    <!-- Tombol Bidang -->
                                    <button 
                                        @click="activeSubMobile === '{{ $bidang }}' ? activeSubMobile = null : activeSubMobile = '{{ $bidang }}'"
                                        class="w-full flex justify-between items-center pl-8 pr-6 py-3 text-xs font-bold uppercase text-gray-600 hover:text-logo-blue hover:bg-blue-50 transition text-left"
                                        :class="activeSubMobile === '{{ $bidang }}' ? 'bg-blue-50 text-logo-blue' : ''"
                                    >
                                        <span class="leading-tight">{{ $bidang }}</span>
                                        @if(count($subs) > 0)
                                            <svg class="w-3 h-3 shrink-0 ml-2 transition-transform" :class="activeSubMobile === '{{ $bidang }}' ? 'rotate-180' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        @endif
                                    </button>

                                    <!-- Level 3: Sub Bidang (Background Merah Muda) -->
                                    @if(count($subs) > 0)
                                        <div x-show="activeSubMobile === '{{ $bidang }}'" x-collapse class="bg-red-50 border-l-4 border-red-500 ml-8">
                                            <!-- Link ke Utama Bidang -->
                                            <a href="{{ route('organisasi.show', ['category' => $bidang]) }}" class="block pl-4 py-2 text-[10px] font-extrabold text-red-700 uppercase tracking-widest border-b border-red-100 hover:bg-red-100">
                                                Buka Halaman Utama
                                            </a>
                                            <!-- List Sub Tim -->
                                            @foreach($subs as $sub)
                                                <a href="{{ route('organisasi.sub', ['category' => $bidang, 'sub_category' => $sub]) }}" class="block pl-4 py-2.5 text-xs font-bold text-gray-700 hover:text-red-700 hover:bg-red-100 transition items-center border-b border-red-100 last:border-0">
                                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-2"></span>
                                                    {{ $sub }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- Direct Link jika tidak ada sub -->
                                        <div x-init="$watch('activeSubMobile', val => { if(val==='{{ $bidang }}') window.location.href='{{ route('organisasi.show', ['category' => $bidang]) }}' })"></div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- 6. PETUGAS LITURGI (Dropdown) -->
                <div x-data="{ expanded: {{ request()->is('petugas*') || request()->is('jadwal-petugas') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-sm font-bold uppercase text-gray-700 hover:bg-gray-50 focus:outline-none {{ request()->is('petugas*') ? 'text-logo-blue' : '' }}">
                        <span>Petugas Liturgi</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" x-collapse class="bg-gray-50 border-t border-gray-100">
                        @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                        <a href="{{ route('petugas.role', ['role' => $tugas]) }}" class="block pl-10 pr-4 py-3 text-xs font-bold text-gray-600 hover:text-logo-blue border-b border-gray-200">
                            {{ $tugas }}
                        </a>
                        @endforeach
                        <a href="/jadwal-petugas" class="block pl-10 pr-4 py-3 text-xs font-bold text-white bg-logo-blue text-center">LIHAT SEMUA JADWAL</a>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="grow">
        @hasSection('header')
            <header class="bg-white shadow-sm mb-6">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold text-logo-blue">
                        @yield('header')
                    </h1>
                </div>
            </header>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-logo-blue text-white mt-auto border-t-4 border-logo-red relative z-10">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- KOLOM 1: IDENTITAS, KONTAK & SOSMED -->
                <div class="space-y-6">
                    
                    <!-- A. Logo & Nama Gereja -->
                    <div class="flex items-center gap-4">
                        <!-- PERBAIKAN LOGO DISINI -->
                        <!-- Menggunakan Flexbox center agar logo pas di tengah lingkaran -->
                        <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center p-1 shrink-0 overflow-hidden border-2 border-blue-200/30">
                            <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" 
                                 alt="Logo Gereja"
                                 class="h-full w-full object-contain">
                        </div>
                        
                        <div>
                            <h3 class="font-bold text-lg leading-tight uppercase tracking-wide">Gereja St. Ignatius Loyola</h3>
                            <p class="text-xs text-blue-200 font-semibold tracking-widest mt-1">TEMANGGAL</p>
                        </div>
                    </div>

                    <!-- B. Alamat & Kontak -->
                    <div>
                        <h4 class="text-logo-yellow text-xs font-bold uppercase tracking-widest mb-2">Alamat & Kontak</h4>
                        <p class="text-sm leading-relaxed text-gray-100 opacity-90">
                            Temanggal II Rt 006 Rw 002, Purwomartani, Kalasan, Sleman, DI Yogyakarta, 55571.
                        </p>
                        <p class="text-sm mt-2 text-gray-100">
                            <span class="font-bold">Telp/WA:</span> +62 858-7620-4359 <br>
                            <span class="font-bold">Email:</span> st.ignatius@gmail.com 
                        </p>
                    </div>

                    <!-- C. Sosial Media (Icon Clickable) -->
                    <div>
                        <h4 class="text-logo-yellow text-xs font-bold uppercase tracking-widest mb-3">Ikuti Kami</h4>
                        <div class="flex gap-4">
                            
                            <!-- Instagram -->
                            <a href="https://www.instagram.com/komsosgsit/" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-800 hover:bg-gradient-to-tr hover:from-yellow-400 hover:via-red-500 hover:to-purple-500 text-white transition duration-300 shadow-md group" title="Instagram">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>

                            <!-- Facebook -->
                            <a href="https://web.facebook.com/profile.php?id=61586425490209" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-800 hover:bg-[#1877F2] text-white transition duration-300 shadow-md group" title="Facebook">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                            </a>

                            <!-- TikTok -->
                            <a href="https://www.tiktok.com/@komsoskalasantengah?is_from_webapp=1&sender_device=pc" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-800 hover:bg-black text-white transition duration-300 shadow-md group" title="TikTok">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93v6.16c0 2.52-1.12 4.84-2.9 6.24-1.72 1.36-4.04 1.86-6.23 1.29-1.92-.51-3.61-1.77-4.52-3.52-.94-1.81-.97-4.04-.03-5.87 1.05-2.02 3.12-3.37 5.37-3.46h.01v4.03c-.94.06-1.84.59-2.38 1.39-.56.84-.66 1.95-.27 2.87.39.92 1.25 1.59 2.24 1.74 1.21.18 2.45-.19 3.29-1.07.72-.76 1.05-1.83 1.05-2.88.01-6.73 0-13.46 0-20.19z"/></svg>
                            </a>

                        </div>
                    </div>
                </div>

                <!-- KOLOM 2: TAUTAN CEPAT (POSISI DITENGAHKAN) -->
                <!-- 'flex flex-col md:items-center' membuat blok ini ke tengah -->
                <div class="flex flex-col md:items-center">
                    <!-- 'w-full md:w-auto' memastikan konten di dalamnya tetap rata kiri relatif satu sama lain -->
                    <div class="w-full md:w-auto">
                        <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Tautan Cepat</h3>
                        <ul class="space-y-3 text-sm">
                            <li><a href="/" class="transition duration-300 flex items-center {{ request()->is('/') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}"><svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Beranda</a></li>
                            <li><a href="/sejarah" class="transition duration-300 flex items-center {{ request()->is('sejarah*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}"><svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Sejarah Gereja</a></li>
                            <li><a href="/pengumuman" class="transition duration-300 flex items-center {{ request()->is('pengumuman*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}"><svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Pengumuman & Berita</a></li>
                            <li><a href="/kegiatan" class="transition duration-300 flex items-center {{ request()->is('kegiatan*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}"><svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Kegiatan Umat</a></li>
                            <li><a href="/teritorial" class="transition duration-300 flex items-center {{ request()->is('teritorial*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}"><svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Peta Wilayah</a></li>
                            <li><a href="https://gerejakalasan.org/" target="_blank" class="transition duration-300 flex items-center text-gray-100 hover:text-logo-yellow"><svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg> Paroki Kalasan</a></li>
                        </ul>
                    </div>
                </div>

                <!-- KOLOM 3: KRITIK & SARAN -->
                <div>
                    <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Kritik & Saran</h3>
                    
                    @if(session('success_feedback'))
                        <div class="mb-4 bg-green-500/20 border border-green-400 text-green-200 px-4 py-3 rounded-lg relative text-sm animate-pulse">
                            {{ session('success_feedback') }}
                        </div>
                    @endif

                    <form action="{{ route('feedback.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <textarea name="message" rows="3" required class="w-full bg-blue-900/50 border border-blue-600/50 text-white rounded-xl p-4 text-sm focus:ring-2 focus:ring-logo-yellow focus:border-transparent outline-none placeholder-gray-400 transition" placeholder="Tulis masukan Anda untuk kemajuan gereja..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-logo-red hover:bg-red-700 text-white text-sm font-bold py-3 px-6 rounded-xl transition duration-300 shadow-lg hover:shadow-red-900/50 flex items-center justify-center group">
                            <span>Kirim Pesan</span>
                            <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>

            </div>

            <!-- COPYRIGHT -->
            <div class="border-t border-blue-800/50 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-blue-200">
                <p>&copy; {{ date('Y') }} Gereja St. Ignatius Loyola Temanggal.</p>
                <p class="mt-2 md:mt-0 flex items-center">
                    <span class="opacity-70">Develop with</span> 
                    <svg class="w-4 h-4 mx-1 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg> 
                    <span class="opacity-70">by @komsosgsit</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- POP-UP -->
    <div id="development-popup" class="popup-overlay fixed inset-0 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
        <div class="popup-content relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-200" style="background-color: #ffffff;">
            <div class="bg-logo-blue p-6 text-center relative overflow-hidden" style="background-color: #003399;">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <h3 class="text-2xl font-bold text-white tracking-wide relative z-10">Website Dalam Pengembangan</h3>
            </div>
            <div class="p-8 text-center bg-white" style="background-color: #ffffff;">
                <p class="text-gray-600 text-base leading-relaxed mb-6">
                    Selamat datang di website resmi <strong>Gereja St. Ignatius Loyola Temanggal</strong>.
                    <br><br>
                    Saat ini website masih dalam pengembangan dan penyempurnaan data. 
                    Jika Anda menemukan kesalahan atau memiliki saran, mohon sampaikan melalui kolom:
                </p>
                <div class="inline-flex items-center justify-center px-4 py-2 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg text-sm font-bold mb-8">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Kritik & Saran (Berada dibawah)
                </div>
                <button onclick="closePopup()" class="w-full bg-logo-red hover:bg-red-800 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200" style="background-color: #DC2626;">
                    Saya Mengerti, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- Javascript Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (!sessionStorage.getItem('seen_development_popup')) {
                setTimeout(() => {
                    const popup = document.getElementById('development-popup');
                    if(popup) popup.classList.add('active');
                }, 500);
            }
        });
        function closePopup() {
            const popup = document.getElementById('development-popup');
            if(popup) {
                popup.classList.remove('active');
                sessionStorage.setItem('seen_development_popup', 'true');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>