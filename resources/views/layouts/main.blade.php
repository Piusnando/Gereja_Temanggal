<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- =============================================== -->
    <!-- SEO UTAMA (TITLE & META DESCRIPTION)            -->
    <!-- =============================================== -->
    <title>@yield('title', 'Gereja St. Ignatius Loyola Kalasan Tengah - Temanggal')</title>
    
    <meta name="description" content="@yield('meta_description', 'Website resmi Gereja Santo Ignatius Loyola Kalasan Tengah, Temanggal. Informasi jadwal misa, pengumuman terkini, profil wilayah, dan kegiatan umat Paroki Maria Marganingsih Kalasan.')">
    
    <meta name="keywords" content="Gereja St. Ignatius Loyola, Kalasan Tengah, Temanggal, Gereja Katolik Kalasan, Paroki Maria Marganingsih, Jadwal Misa Kalasan  Tengah, Gereja Katolik Sleman, Yogyakarta">
    <meta name="author" content="Komsos St. Ignatius Loyola">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- =============================================== -->
    <!-- OPEN GRAPH (TAMPILAN SAAT SHARE WA/FB)          -->
    <!-- =============================================== -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Gereja St. Ignatius Loyola Kalasan Tengah')">
    <meta property="og:description" content="@yield('meta_description', 'Pusat informasi dan pelayanan pastoral Gereja Santo Ignatius Loyola Temanggal, Kalasan.')">
    <!-- Gambar default saat share link (Pastikan ada file logo.png atau banner.jpg di public/images) -->
    <meta property="og:image" content="{{ asset('images/logo-default.png') }}">

    <!-- =============================================== -->
    <!-- SCHEMA MARKUP (AGAR TERDETEKSI SEBAGAI LOKASI)  -->
    <!-- =============================================== -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CatholicChurch",
      "name": "Gereja Santo Ignatius Loyola Kalasan Tengah",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Temanggal Raya, Temanggal II Rt 006 Rw 002",
        "addressLocality": "Purwomartani, Kalasan",
        "addressRegion": "Sleman, DI Yogyakarta",
        "postalCode": "55571",
        "addressCountry": "ID"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": -7.748685, 
        "longitude": 110.453982
      },
      "url": "{{ url('/') }}",
      "telephone": "+62274xxxxxxx" 
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- KONFIGURASI WARNA TAILWIND -->
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
        /* Animasi Pop-up */
        .popup-overlay { opacity: 0; visibility: hidden; transition: all 0.3s ease-in-out; z-index: 9999 !important; }
        .popup-overlay.active { opacity: 1; visibility: visible; }
        .popup-content { transform: scale(0.95); opacity: 0; transition: all 0.3s ease-out; }
        .popup-overlay.active .popup-content { transform: scale(1); opacity: 1; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 relative z-50 shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24"> 
                
                <!-- HEADER KIRI: Logo & Nama -->
                <div class="flex items-center gap-4">
                    <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" 
                        alt="Logo Gereja" 
                        class="h-14 w-auto object-contain">
                    <div class="flex flex-col justify-center">
                        <a href="/" class="text-lg md:text-xl font-extrabold text-logo-blue leading-tight uppercase tracking-wide">
                            Gereja St. Ignatius Loyola<br class="hidden md:block"> 
                            <span class="text-logo-red">Kalasan Tengah</span>
                        </a>
                        <span class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-widest mt-0.5 uppercase">
                            Paroki Maria Marganingsih Kalasan
                        </span>
                    </div>
                </div>

                <!-- HEADER KANAN: Menu Desktop -->
                <div class="hidden lg:flex lg:items-center lg:gap-x-8">
                    
                    <!-- 1. BERANDA -->
                    <a href="/" class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 {{ request()->path() === '/' ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Beranda</a>

                    <!-- 2. SEJARAH -->
                    <a href="/sejarah" class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 {{ request()->is('sejarah*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Sejarah</a>

                    <!-- 3. PENGUMUMAN -->
                    <a href="/pengumuman" class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 {{ request()->is('pengumuman*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Pengumuman</a>

                    <!-- 4. TERITORIAL (DROPDOWN) -->
                    <div class="relative h-24 flex items-center group" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                        <button @click="dropdownOpen = ! dropdownOpen" class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('teritorial*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Teritorial <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': dropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="dropdownOpen" x-transition class="nav-dropdown absolute top-[80%] left-0 w-64 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden" style="display: none;">
                             <div class="py-2">
                                 @if(isset($globalTerritories))
                                     @foreach($globalTerritories as $wilayah)
                                        <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-logo-blue font-medium border-b border-gray-50 last:border-0 transition">Wilayah {{ $wilayah->name }}</a>
                                     @endforeach
                                 @endif
                                 <a href="/teritorial" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase hover:bg-blue-800">Lihat Peta Besar</a>
                             </div>
                        </div>
                    </div>

                    <!-- 5. ORGANISASI (DROPDOWN) -->
                    <div class="relative h-24 flex items-center group" x-data="{ orgOpen: false }" @click.away="orgOpen = false">
                        <button @click="orgOpen = ! orgOpen" class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('organisasi*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Organisasi <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': orgOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="orgOpen" x-transition class="nav-dropdown absolute top-[80%] left-0 w-56 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden" style="display: none;">
                                <div class="py-2">
                                    @foreach(['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor'] as $org)
                                    <a href="{{ route('organisasi.show', ['category' => $org]) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-logo-blue font-medium border-b border-gray-50 last:border-0 transition">{{ $org }}</a>
                                    @endforeach
                                </div>
                        </div>
                    </div>

                    <!-- 6. PETUGAS LITURGI (DROPDOWN) -->
                    <div class="relative h-24 flex items-center group" x-data="{ liturgiOpen: false }" @click.away="liturgiOpen = false">
                        <button @click="liturgiOpen = ! liturgiOpen" class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('petugas/*') || request()->is('jadwal-petugas') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Petugas Liturgi <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': liturgiOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="liturgiOpen" x-transition class="nav-dropdown absolute top-[80%] right-0 w-48 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden" style="display: none;">
                             <div class="py-2">
                                 @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                                 <a href="{{ route('petugas.role', ['role' => $tugas]) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-logo-blue font-medium border-b border-gray-50 last:border-0 transition">{{ $tugas }}</a>
                                 @endforeach
                                 <a href="/jadwal-petugas" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase hover:bg-blue-800">Lihat Semua Jadwal</a>
                             </div>
                        </div>
                    </div>

                </div>

                <!-- Mobile Menu Button -->
                <div class="-mr-2 flex items-center lg:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                        <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- MOBILE MENU DROPDOWN (RESPONSIVE) -->
        <!-- ============================================== -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-xl absolute w-full left-0 z-50 overflow-y-auto max-h-[85vh] transition-all duration-300 ease-in-out">
            <div class="py-2 pb-6 space-y-1">
                
                <a href="/" class="block px-6 py-3 border-l-4 {{ request()->path() === '/' ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }} font-bold uppercase transition">
                    Beranda
                </a>
                
                <a href="/sejarah" class="block px-6 py-3 border-l-4 {{ request()->is('sejarah*') ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }} font-bold uppercase transition">
                    Sejarah
                </a>

                <a href="/pengumuman" class="block px-6 py-3 border-l-4 {{ request()->is('pengumuman*') ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }} font-bold uppercase transition">
                    Pengumuman
                </a>

                <!-- Mobile Teritorial -->
                <div x-data="{ expanded: {{ request()->is('teritorial*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" 
                            class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-gray-700 font-bold uppercase hover:bg-gray-50 focus:outline-none {{ request()->is('teritorial*') ? 'text-logo-blue' : '' }}">
                        <span>Teritorial</span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @if(isset($globalTerritories))
                            @foreach($globalTerritories as $wilayah)
                            <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block pl-10 pr-4 py-2 text-sm text-gray-600 hover:text-logo-blue font-medium">
                                Wilayah {{ $wilayah->name }}
                            </a>
                            @endforeach
                        @endif
                        <a href="/teritorial" class="block pl-10 pr-4 py-2 text-sm text-logo-blue font-bold">
                            Lihat Peta Besar →
                        </a>
                    </div>
                </div>

                <!-- Mobile Organisasi -->
                <div x-data="{ expanded: {{ request()->is('organisasi*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" 
                            class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-gray-700 font-bold uppercase hover:bg-gray-50 focus:outline-none {{ request()->is('organisasi*') ? 'text-logo-blue' : '' }}">
                        <span>Organisasi</span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @foreach(['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor'] as $org)
                        <a href="{{ route('organisasi.show', ['category' => $org]) }}" class="block pl-10 pr-4 py-2 text-sm text-gray-600 hover:text-logo-blue font-medium">
                            {{ $org }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Petugas Liturgi -->
                <div x-data="{ expanded: {{ request()->is('petugas*') || request()->is('jadwal-petugas') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" 
                            class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-gray-700 font-bold uppercase hover:bg-gray-50 focus:outline-none {{ request()->is('petugas*') ? 'text-logo-blue' : '' }}">
                        <span>Petugas Liturgi</span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                        <a href="{{ route('petugas.role', ['role' => $tugas]) }}" class="block pl-10 pr-4 py-2 text-sm text-gray-600 hover:text-logo-blue font-medium">
                            {{ $tugas }}
                        </a>
                        @endforeach
                        <a href="/jadwal-petugas" class="block pl-10 pr-4 py-2 text-sm text-logo-blue font-bold">
                            Lihat Semua Jadwal →
                        </a>
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

    <!-- Footer (WARNA KONSISTEN) -->
    <!-- Menggunakan bg-logo-blue yang sudah didefinisikan di config -->
    <footer class="bg-logo-blue text-white mt-auto border-t-4 border-logo-red relative z-10">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Kontak -->
                <div>
                    <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Kontak Kami</h3>
                    <p class="text-sm leading-relaxed mb-4 text-gray-100">
                        <span class="block font-bold text-white mb-1">Gereja St. Ignatius Loyola Temanggal</span>
                        Temanggal II Rt 006 Rw 002, Purwomartani, Kalasan, Sleman DI Yogyakarta, 55571.
                    </p>
                </div>
                <!-- Tautan Cepat -->
                <div>
                    <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        
                        <!-- 1. BERANDA -->
                        <li>
                            <a href="/" 
                            class="transition duration-300 {{ request()->is('/') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">
                                Beranda
                            </a>
                        </li>

                        <!-- 2. SEJARAH -->
                        <li>
                            <a href="/sejarah" 
                            class="transition duration-300 {{ request()->is('sejarah*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">
                                Sejarah Gereja
                            </a>
                        </li>

                        <!-- 3. PENGUMUMAN -->
                        <li>
                            <a href="/pengumuman" 
                            class="transition duration-300 {{ request()->is('pengumuman*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">
                                Pengumuman
                            </a>
                        </li>

                        <!-- 4. TERITORIAL -->
                        <li>
                            <a href="/teritorial" 
                            class="transition duration-300 {{ request()->is('teritorial*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">
                                Pembagian Wilayah
                            </a>
                        </li>

                        <!-- 5. PAROKI INDUK (Link Luar) -->
                        <li>
                            <a href="https://gerejakalasan.org/" target="_blank" class="text-gray-100 hover:text-logo-yellow transition duration-300 flex items-center">
                                Paroki Maria Marganingsih Kalasan
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- Kritik Saran -->
                <div>
                    <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Kritik & Saran</h3>
                    @if(session('success_feedback'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded relative text-sm">
                            {{ session('success_feedback') }}
                        </div>
                    @endif
                    <form action="{{ route('feedback.store') }}" method="POST" class="space-y-2">
                        @csrf
                        <textarea name="message" rows="3" required class="w-full bg-blue-800/50 border border-blue-600 text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-logo-yellow focus:outline-none placeholder-gray-300" placeholder="Tulis pesan Anda di sini..."></textarea>
                        <button type="submit" class="bg-logo-red hover:bg-red-800 text-white text-sm font-bold py-2 px-4 rounded-md transition duration-150 w-full md:w-auto shadow-md">Kirim Pesan</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-12 pt-8 text-center text-sm text-blue-200">
                &copy; {{ date('Y') }} Gereja St. Ignatius Loyola Temanggal. by @piusnando_
            </div>
        </div>
    </footer>
    <!-- ========================================== -->
    <!-- CUSTOM POPUP UI (FIXED TAMPILAN) -->
    <!-- ========================================== -->
    
    <style>
        /* Animasi Fade In/Out */
        .popup-overlay {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
            z-index: 9999 !important; /* WAJIB TINGGI AGAR TIDAK TERTUTUP */
        }
        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .popup-content {
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.3s ease-out;
        }
        .popup-overlay.active .popup-content {
            transform: scale(1);
            opacity: 1;
        }
    </style>

    <!-- HTML Modal -->
    <div id="development-popup" class="popup-overlay fixed inset-0 flex items-center justify-center px-4">
        
        <!-- Backdrop Hitam (Pemisah) -->
        <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"></div>

        <!-- Konten Putih -->
        <!-- Tambahkan style background-color manual agar pasti putih solid -->
        <div class="popup-content relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-200" style="background-color: #ffffff;">
            
            <!-- Header Biru -->
            <div class="bg-logo-blue p-6 text-center relative overflow-hidden" style="background-color: #003399;">
                <!-- Hiasan Background -->
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                
                <!-- Icon Info -->
                <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3 backdrop-blur-md border border-white/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white tracking-wide relative z-10">Website Dalam Pengembangan</h3>
            </div>

            <!-- Body Content -->
            <div class="p-8 text-center bg-white" style="background-color: #ffffff;">
                <p class="text-gray-600 text-base leading-relaxed mb-6">
                    Selamat datang di website resmi <strong>Gereja St. Ignatius Loyola Temanggal</strong>.
                    <br><br>
                    Saat ini website masih dalam tahap uji coba dan penyempurnaan data. 
                    Jika Anda menemukan kesalahan atau memiliki saran, mohon sampaikan melalui kolom:
                </p>
                
                <div class="inline-flex items-center justify-center px-4 py-2 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg text-sm font-bold mb-8">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Kritik & Saran (di bagian Footer)
                </div>

                <!-- Tombol Aksi -->
                <button onclick="closePopup()" 
                        class="w-full bg-logo-red hover:bg-red-800 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200"
                        style="background-color: #DC2626;"> <!-- Paksa warna merah -->
                    Saya Mengerti, Lanjutkan
                </button>
            </div>

        </div>
    </div>

    <!-- Javascript Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah user sudah pernah menutup popup
            if (!localStorage.getItem('seen_development_popup')) {
                // Tampilkan popup (tambah class active)
                setTimeout(() => {
                    const popup = document.getElementById('development-popup');
                    if(popup) popup.classList.add('active');
                }, 500);
            }
        });

        function closePopup() {
            const popup = document.getElementById('development-popup');
            if(popup) {
                // 1. Hilangkan class active (animasi keluar)
                popup.classList.remove('active');
                // 2. Simpan ke memori browser agar tidak muncul lagi
                localStorage.setItem('seen_development_popup', 'true');
            }
        }
    </script>


    @stack('scripts')
</body>
</html>