<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ================= SEO TAGS ================= -->
    <title>@yield('title', 'Gereja St. Ignatius Loyola Kalasan Tengah')</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik')">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24"> 

                <!-- LOGO & NAMA GEREJA -->
                <div class="flex items-center gap-4">
                    <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" alt="Logo Gereja" class="h-14 w-auto object-contain">
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

                <!-- MENU DESKTOP -->
                <div class="hidden lg:flex lg:items-center lg:gap-x-8">
                    <a href="/" class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 {{ request()->path() === '/' ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Beranda</a>
                    <a href="/sejarah" class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 {{ request()->is('sejarah*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Sejarah</a>
                    <a href="/pengumuman" class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 {{ request()->is('pengumuman*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Pengumuman</a>

                    <!-- Dropdown Teritorial -->
                    <div class="relative h-24 flex items-center group" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                        <button @click="dropdownOpen = ! dropdownOpen" class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('teritorial*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Teritorial <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': dropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="dropdownOpen" x-transition x-cloak class="nav-dropdown absolute top-[80%] left-0 w-64 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                             <div class="py-2">
                                 @if(isset($globalTerritories))
                                     @foreach($globalTerritories as $wilayah)
                                        <a href="{{ route('teritorial.show', $wilayah->slug) }}" 
                                           class="block px-4 py-3 text-sm font-medium border-b border-gray-50 last:border-0 transition 
                                           {{ request()->fullUrlIs(route('teritorial.show', $wilayah->slug)) ? 'bg-blue-50 text-logo-blue font-bold' : 'text-gray-700 hover:bg-blue-50 hover:text-logo-blue' }}">
                                            Wilayah {{ $wilayah->name }}
                                        </a>
                                     @endforeach
                                 @endif
                                 <a href="/teritorial" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase hover:bg-blue-800">Lihat Peta Besar</a>
                             </div>
                        </div>
                    </div>

                    <!-- Dropdown Organisasi (PERBAIKAN: Active State) -->
                    <div class="relative h-24 flex items-center group" x-data="{ orgOpen: false }" @click.away="orgOpen = false">
                        <button @click="orgOpen = ! orgOpen" class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('organisasi*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Organisasi <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': orgOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="orgOpen" x-transition x-cloak class="nav-dropdown absolute top-[80%] left-0 w-56 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                            <div class="py-2">
                                @foreach(['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor'] as $org)
                                <a href="{{ route('organisasi.show', ['category' => $org]) }}" 
                                   class="block px-4 py-3 text-sm font-medium border-b border-gray-50 last:border-0 transition 
                                   {{ request()->fullUrlIs(route('organisasi.show', ['category' => $org])) ? 'bg-blue-50 text-logo-blue font-bold' : 'text-gray-700 hover:bg-blue-50 hover:text-logo-blue' }}">
                                    {{ $org }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Petugas (PERBAIKAN: Active State) -->
                    <div class="relative h-24 flex items-center group" x-data="{ liturgiOpen: false }" @click.away="liturgiOpen = false">
                        <button @click="liturgiOpen = ! liturgiOpen" class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('petugas*') || request()->is('jadwal-petugas') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Petugas Liturgi <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': liturgiOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="liturgiOpen" x-transition x-cloak class="nav-dropdown absolute top-[80%] right-0 w-48 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                             <div class="py-2">
                                 @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                                 <a href="{{ route('petugas.role', ['role' => $tugas]) }}" 
                                    class="block px-4 py-3 text-sm font-medium border-b border-gray-50 last:border-0 transition 
                                    {{ request()->fullUrlIs(route('petugas.role', ['role' => $tugas])) ? 'bg-blue-50 text-logo-blue font-bold' : 'text-gray-700 hover:bg-blue-50 hover:text-logo-blue' }}">
                                     {{ $tugas }}
                                 </a>
                                 @endforeach
                                 <a href="/jadwal-petugas" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase hover:bg-blue-800">Lihat Semua Jadwal</a>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Menu Mobile -->
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

        <!-- MOBILE MENU (RESPONSIVE) -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-xl absolute w-full left-0 z-50 overflow-y-auto max-h-[85vh]">
            <div class="py-2 pb-6 space-y-1">
                <a href="/" class="block px-6 py-3 border-l-4 {{ request()->path() === '/' ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }} font-bold uppercase transition">Beranda</a>
                <a href="/sejarah" class="block px-6 py-3 border-l-4 {{ request()->is('sejarah*') ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }} font-bold uppercase transition">Sejarah</a>
                <a href="/pengumuman" class="block px-6 py-3 border-l-4 {{ request()->is('pengumuman*') ? 'bg-red-50 text-logo-red border-logo-red' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }} font-bold uppercase transition">Pengumuman</a>

                <!-- Mobile Teritorial -->
                <div x-data="{ expanded: {{ request()->is('teritorial*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-gray-700 font-bold uppercase hover:bg-gray-50 focus:outline-none {{ request()->is('teritorial*') ? 'text-logo-blue' : '' }}">
                        <span>Teritorial</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @if(isset($globalTerritories))
                            @foreach($globalTerritories as $wilayah)
                            <a href="{{ route('teritorial.show', $wilayah->slug) }}" 
                               class="block pl-10 pr-4 py-2 text-sm font-medium transition {{ request()->fullUrlIs(route('teritorial.show', $wilayah->slug)) ? 'text-logo-red font-bold' : 'text-gray-600 hover:text-logo-blue' }}">
                                Wilayah {{ $wilayah->name }}
                            </a>
                            @endforeach
                        @endif
                        <a href="/teritorial" class="block pl-10 pr-4 py-2 text-sm text-logo-blue font-bold">Lihat Peta Besar →</a>
                    </div>
                </div>

                <!-- Mobile Organisasi (PERBAIKAN) -->
                <div x-data="{ expanded: {{ request()->is('organisasi*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-gray-700 font-bold uppercase hover:bg-gray-50 focus:outline-none {{ request()->is('organisasi*') ? 'text-logo-blue' : '' }}">
                        <span>Organisasi</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @foreach(['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor'] as $org)
                        <a href="{{ route('organisasi.show', ['category' => $org]) }}" 
                           class="block pl-10 pr-4 py-2 text-sm font-medium transition {{ request()->fullUrlIs(route('organisasi.show', ['category' => $org])) ? 'text-logo-red font-bold' : 'text-gray-600 hover:text-logo-blue' }}">
                            {{ $org }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Petugas Liturgi (PERBAIKAN) -->
                <div x-data="{ expanded: {{ request()->is('petugas*') || request()->is('jadwal-petugas') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between items-center px-6 py-3 border-l-4 border-transparent text-gray-700 font-bold uppercase hover:bg-gray-50 focus:outline-none {{ request()->is('petugas*') ? 'text-logo-blue' : '' }}">
                        <span>Petugas Liturgi</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                        <a href="{{ route('petugas.role', ['role' => $tugas]) }}" 
                           class="block pl-10 pr-4 py-2 text-sm font-medium transition {{ request()->fullUrlIs(route('petugas.role', ['role' => $tugas])) ? 'text-logo-red font-bold' : 'text-gray-600 hover:text-logo-blue' }}">
                            {{ $tugas }}
                        </a>
                        @endforeach
                        <a href="/jadwal-petugas" class="block pl-10 pr-4 py-2 text-sm text-logo-blue font-bold">Lihat Semua Jadwal →</a>
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
                        <li><a href="/" class="transition duration-300 {{ request()->is('/') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Beranda</a></li>
                        <li><a href="/sejarah" class="transition duration-300 {{ request()->is('sejarah*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Sejarah Gereja</a></li>
                        <li><a href="/pengumuman" class="transition duration-300 {{ request()->is('pengumuman*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Pengumuman</a></li>
                        <li><a href="/teritorial" class="transition duration-300 {{ request()->is('teritorial*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Pembagian Wilayah</a></li>
                        <li><a href="https://gerejakalasan.org/" class="transition duration-300 text-gray-100 hover:text-logo-yellow flex items-center">Paroki Maria Marganingsih Kalasan <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a></li>
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
                    Saat ini website masih dalam tahap uji coba dan penyempurnaan data. 
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