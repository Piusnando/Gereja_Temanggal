<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO & KATA KUNCI (KONSISTEN) -->
    <title>@yield('title', 'Gereja St. Ignatius Loyola Kalasan Tengah - Temanggal')</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi Gereja Santo Ignatius Loyola Kalasan Tengah, Temanggal. Informasi jadwal misa, pengumuman, dan profil Paroki Maria Marganingsih Kalasan.')">
    <meta name="keywords" content="Gereja Santo Ignatius Loyola, Kalasan Tengah, Temanggal, Gereja Katolik Kalasan, Paroki Maria Marganingsih, Jadwal Misa Sleman, Gereja Katolik Yogyakarta">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'Gereja St. Ignatius Loyola Kalasan Tengah')">
    <meta property="og:description" content="Informasi pelayanan pastoral Gereja Santo Ignatius Loyola Temanggal.">
    <meta property="og:image" content="{{ $globalLogo ?? asset('images/logo-default.png') }}">

    <!-- Fonts & Scripts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        /* Agar saat scroll ke ID tidak tertutup navbar */
        html { scroll-padding-top: 7rem; }
        .nav-dropdown { z-index: 9999 !important; }
        [x-cloak] { display: none !important; }
        .popup-overlay { opacity: 0; visibility: hidden; transition: all 0.3s ease-in-out; z-index: 9999 !important; }
        .popup-overlay.active { opacity: 1; visibility: visible; }
        .popup-content { transform: scale(0.95); opacity: 0; transition: all 0.3s ease-out; }
        .popup-overlay.active .popup-content { transform: scale(1); opacity: 1; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- NAVBAR (SESUAI GAMBAR) -->
    <!-- z-[9999] agar mutlak di depan konten/shadow kartu -->
    <nav class="bg-white sticky top-0 z-9999 shadow-md" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24"> 
                
                <!-- HEADER KIRI: Logo & Nama -->
                <div class="flex items-center gap-4">
                    <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" alt="Logo" class="h-14 w-auto object-contain">
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
                    <!-- Link dengan border-b-4 untuk menu aktif seperti di gambar -->
                    <a href="/" class="h-24 flex items-center text-sm font-bold uppercase border-b-4 transition-all duration-300 {{ request()->is('/') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Beranda</a>
                    <a href="/sejarah" class="h-24 flex items-center text-sm font-bold uppercase border-b-4 transition-all duration-300 {{ request()->is('sejarah*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Sejarah</a>
                    <a href="/pengumuman" class="h-24 flex items-center text-sm font-bold uppercase border-b-4 transition-all duration-300 {{ request()->is('pengumuman*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">Pengumuman</a>

                    <!-- Dropdown Teritorial -->
                    <div class="relative h-24 flex items-center group" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                        <button @click="dropdownOpen = ! dropdownOpen" class="h-full flex items-center text-sm font-bold uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('teritorial*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Teritorial <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': dropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="dropdownOpen" x-transition class="nav-dropdown absolute top-[85%] left-0 w-64 bg-white shadow-xl rounded-xl border border-gray-100 py-2" style="display: none;">
                            @if(isset($globalTerritories))
                                @foreach($globalTerritories as $wilayah)
                                    <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 border-b border-gray-50 last:border-0 transition">Wilayah {{ $wilayah->name }}</a>
                                @endforeach
                            @endif
                            <a href="/teritorial" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase">Lihat Peta Besar</a>
                        </div>
                    </div>

                    <!-- Organisasi Dropdown -->
                    <div class="relative h-24 flex items-center group" x-data="{ orgOpen: false }" @click.away="orgOpen = false">
                        <button @click="orgOpen = ! orgOpen" class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('organisasi*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Organisasi <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': orgOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="orgOpen" x-transition x-cloak class="nav-dropdown absolute top-[80%] left-0 w-56 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                                <div class="py-2">
                                    @foreach(['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor'] as $org)
                                    <a href="{{ route('organisasi.show', ['category' => $org]) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-logo-blue font-medium border-b border-gray-50 last:border-0 transition">{{ $org }}</a>
                                    @endforeach
                                </div>
                        </div>
                    </div>

                    <!-- Liturgi Dropdown -->
                    <div class="relative h-24 flex items-center group" x-data="{ liturgiOpen: false }" @click.away="liturgiOpen = false">
                        <button @click="liturgiOpen = ! liturgiOpen" class="h-full flex items-center text-sm font-bold uppercase border-b-4 transition-all duration-300 focus:outline-none {{ request()->is('petugas/*') || request()->is('jadwal-petugas') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Jawdal Petugas <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': liturgiOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="liturgiOpen" x-transition class="nav-dropdown absolute top-[85%] right-0 w-48 bg-white shadow-xl rounded-xl border border-gray-100 py-2" style="display: none;">
                             @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $role)
                                <a href="{{ route('petugas.role', ['role' => $role]) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 border-b border-gray-50 last:border-0 transition text-nowrap">{{ $role }}</a>
                             @endforeach
                             <a href="/jadwal-petugas" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase">Lihat Semua Jadwal</a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="open = ! open" class="p-2 rounded-md text-gray-400 hover:bg-gray-100 transition">
                        <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-xl absolute w-full left-0 z-50">
            <div class="py-4 space-y-1">
                <a href="/" class="block px-6 py-3 font-bold uppercase {{ request()->is('/') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-700' }}">Beranda</a>
                <a href="/sejarah" class="block px-6 py-3 font-bold uppercase {{ request()->is('sejarah*') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-700' }}">Sejarah</a>
                <a href="/pengumuman" class="block px-6 py-3 font-bold uppercase {{ request()->is('pengumuman*') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-700' }}">Pengumuman</a>
                
                <div x-data="{ expanded: {{ request()->is('teritorial*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between px-6 py-3 text-gray-700 font-bold uppercase">
                        <span>Teritorial</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @if(isset($globalTerritories))
                            @foreach($globalTerritories as $wilayah)
                            <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block pl-10 py-2 text-sm text-gray-600">Wilayah {{ $wilayah->name }}</a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div x-data="{ expanded: {{ request()->is('organisasi*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between px-6 py-3 text-gray-700 font-bold uppercase">
                        <span>Organisasi</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" class="bg-gray-50 py-2">
                        @foreach(['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor'] as $org)
                        <a href="{{ route('organisasi.show', ['category' => $org]) }}" class="block pl-10 py-2 text-sm text-gray-600">{{ $org }}</a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Mobile Liturgi (Accordion) -->
                <div x-data="{ expanded: {{ request()->is('petugas*') || request()->is('jadwal-petugas') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full flex justify-between px-6 py-3 text-gray-700 font-bold uppercase hover:bg-gray-50 focus:outline-none">
                        <span>Jadwal Petugas</span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="expanded" x-transition class="bg-gray-50 py-2">
                        @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                            <a href="{{ route('petugas.role', ['role' => $tugas]) }}" 
                            class="block pl-10 py-2 text-sm {{ request()->fullUrlIs(route('petugas.role', $tugas)) ? 'text-logo-red font-bold' : 'text-gray-600' }} hover:text-logo-blue transition">
                                {{ $tugas }}
                            </a>
                        @endforeach
                        
                        <!-- Link Tambahan untuk Semua Jadwal -->
                        <a href="/jadwal-petugas" 
                        class="block pl-10 py-3 text-sm {{ request()->is('jadwal-petugas') ? 'text-logo-red' : 'text-logo-blue' }} font-bold border-t border-gray-200 mt-1">
                            Lihat Semua Jadwal →
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- CONTENT AREA -->
    <main class="grow w-full">
        {{-- FIX CELAH PUTIH: Hanya render header jika isinya benar-benar ada --}}
        @hasSection('header')
            @php $h = trim($__env->yieldContent('header')); @endphp
            @if($h !== '')
                <header class="bg-white shadow-sm mb-6">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-2xl font-bold text-logo-blue">{{ $h }}</h1>
                    </div>
                </header>
            @endif
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-logo-blue text-white mt-auto border-t-4 border-logo-red relative z-10">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                <div>
                    <h3 class="text-logo-yellow font-bold mb-4 uppercase tracking-wider">Kontak Kami</h3>
                    <p class="leading-relaxed">Gereja St. Ignatius Loyola Temanggal<br>Temanggal II Rt 006 Rw 002, Kalasan, Sleman 55571.</p>
                </div>
                <div>
                    <h3 class="text-logo-yellow font-bold mb-4 uppercase tracking-wider">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="{{ request()->is('/') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Beranda</a></li>
                        <li><a href="/sejarah" class="{{ request()->is('sejarah*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Sejarah</a></li>
                        <li><a href="/pengumuman" class="{{ request()->is('pengumuman*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Pengumuman</a></li>
                        <li><a href="/teritorial" class="{{ request()->is('terirotial*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Teritorial Gereja</a></li>
                        <li><a href="/jadwal-petugas" class="{{ request()->is('jadawal-petugas*') ? 'text-logo-yellow font-bold' : 'text-gray-100 hover:text-logo-yellow' }}">Jadwal Petugas Liturgi</a></li>
                        <li><a href="https://gerejakalasan.org/" target="_blank" class="hover:text-logo-yellow transition">Paroki Maria Marganingsih Kalasan</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-logo-yellow font-bold mb-4 uppercase tracking-wider">Kritik & Saran</h3>
                    <form action="{{ route('feedback.store') }}" method="POST" class="space-y-2">
                        @csrf
                        <textarea name="message" rows="2" required class="w-full bg-blue-800/50 border border-blue-600 rounded p-2 text-white placeholder-blue-300 focus:ring-2 focus:ring-logo-yellow outline-none" placeholder="Tulis pesan..."></textarea>
                        <button type="submit" class="bg-logo-red hover:bg-red-800 text-white text-sm font-bold py-2 px-4 rounded shadow-md">Kirim</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-12 pt-8 text-center text-sm text-blue-200">
                &copy; {{ date('Y') }} Gereja St. Ignatius Loyola Temanggal.
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
    <!-- SCRIPTS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (!sessionStorage.getItem('seen_development_popup')) {
                setTimeout(() => { 
                    const p = document.getElementById('development-popup');
                    if(p) p.classList.add('active'); 
                }, 500);
            }
        });
        function closePopup() {
            const p = document.getElementById('development-popup');
            if(p) {
                p.classList.remove('active');
                sessionStorage.setItem('seen_development_popup', 'true');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>