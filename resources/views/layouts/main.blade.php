<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gereja St. Ignatius Loyola')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* WARNA KONSISTEN (BRANDING) */
        .text-logo-blue { color: #003399; }
        .bg-logo-blue { background-color: #003399; }
        
        /* Merah Utama (Digunakan di seluruh website) */
        .text-logo-red { color: #DC2626; } /* Setara Red-600 */
        .bg-logo-red { background-color: #DC2626; }
        .border-logo-red { border-color: #DC2626; }
        
        .text-logo-yellow { color: #FFCC00; }
        
        /* Fix Dropdown agar tidak tertutup */
        .nav-dropdown { z-index: 9999 !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 relative z-50 shadow-sm transition-all duration-300" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                
                <!-- HEADER KIRI: Logo & Nama -->
                <div class="flex justify-start lg:w-0 lg:flex-1">
                    <a href="/" class="flex items-center gap-3 group">
                        <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" 
                             alt="Logo Gereja" 
                             class="h-14 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition duration-300">
                        
                        <div class="flex flex-col justify-center">
                            <span class="text-lg md:text-xl font-extrabold text-logo-blue leading-tight uppercase tracking-wide group-hover:text-blue-800 transition">
                                Gereja St. Ignatius Loyola
                            </span>
                            <span class="text-lg md:text-xl font-extrabold text-logo-red leading-none uppercase tracking-wide hover:opacity-80 transition">
                                Kalasan Tengah
                            </span>
                            <span class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-widest mt-1 uppercase">
                                Paroki Maria Marganingsih Kalasan
                            </span>
                        </div>
                    </a>
                </div>

                <!-- HEADER KANAN: Menu Desktop -->
                <div class="hidden lg:flex lg:items-center lg:gap-x-8">
                    
                    <!-- 1. BERANDA -->
                    <a href="/" 
                       class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 
                       {{ request()->path() === '/' ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Beranda
                    </a>

                    <!-- 2. SEJARAH -->
                    <a href="#" 
                       class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 
                       {{ request()->is('sejarah*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Sejarah
                    </a>

                    <!-- 3. PENGUMUMAN -->
                    <a href="/pengumuman" 
                       class="h-24 flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 
                       {{ request()->is('pengumuman*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Pengumuman
                    </a>

                    <!-- 4. TERITORIAL (DROPDOWN CLICK) -->
                    <div class="relative h-24 flex items-center group" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                        <button @click="dropdownOpen = ! dropdownOpen"
                                class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none
                                {{ request()->is('teritorial*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Teritorial
                            <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': dropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="dropdownOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="nav-dropdown absolute top-[80%] left-0 w-64 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden"
                             style="display: none;">
                             
                             <div class="py-2">
                                 @if(isset($globalTerritories))
                                     @foreach($globalTerritories as $wilayah)
                                        <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-logo-blue font-medium border-b border-gray-50 last:border-0 transition">
                                            Wilayah {{ $wilayah->name }}
                                        </a>
                                     @endforeach
                                 @endif
                                 <a href="/teritorial" class="block px-4 py-3 text-xs text-center text-gray-500 bg-gray-50 font-bold uppercase hover:bg-gray-100">
                                     Lihat Peta Besar
                                 </a>
                             </div>
                        </div>
                    </div>

                    <!-- 5. ORGANISASI (DROPDOWN CLICK) -->
                    <div class="relative h-24 flex items-center group" x-data="{ orgOpen: false }" @click.away="orgOpen = false">
                        <button @click="orgOpen = ! orgOpen"
                                class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none
                                {{ request()->is('organisasi*') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Organisasi
                            <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': orgOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="orgOpen" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="nav-dropdown absolute top-[80%] left-0 w-56 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden"
                                style="display: none;">
                                <div class="py-2">
                                    @foreach(['Pengurus Gereja', 'OMK', 'Misdinar', 'KOMSOS', 'PIA & PIR', 'Mazmur', 'Lektor'] as $org)
                                    <a href="{{ route('organisasi.show', ['category' => $org]) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-logo-blue font-medium border-b border-gray-50 last:border-0 transition">
                                        {{ $org }}
                                    </a>
                                    @endforeach
                                </div>
                        </div>
                    </div>

                    <!-- 6. PETUGAS LITURGI (DROPDOWN CLICK) -->
                    <div class="relative h-24 flex items-center group" x-data="{ liturgiOpen: false }" @click.away="liturgiOpen = false">
                        <button @click="liturgiOpen = ! liturgiOpen"
                                class="h-full flex items-center text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none
                                {{ request()->is('petugas/*') || request()->is('jadwal-petugas') ? 'text-logo-red border-logo-red' : 'text-gray-600 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Petugas Liturgi
                            <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" :class="{'rotate-180': liturgiOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="liturgiOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="nav-dropdown absolute top-[80%] right-0 w-48 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden"
                             style="display: none;">
                             
                             <div class="py-2">
                                 @foreach(['Misdinar', 'Lektor', 'Mazmur', 'Paduan Suara', 'Organis', 'Parkir'] as $tugas)
                                 <a href="{{ route('petugas.role', ['role' => $tugas]) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-logo-blue font-medium border-b border-gray-50 last:border-0 transition">
                                     {{ $tugas }}
                                 </a>
                                 @endforeach
                                 <a href="/jadwal-petugas" class="block px-4 py-3 text-xs text-center text-white bg-logo-blue font-bold uppercase hover:bg-blue-800">
                                     Lihat Semua Jadwal
                                 </a>
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

        <!-- Mobile Menu Dropdown -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-xl absolute w-full left-0 z-50 overflow-y-auto max-h-[85vh] transition-all duration-300 ease-in-out">
            <div class="pt-2 pb-6 space-y-1 px-4">
                
                <a href="/" class="block px-4 py-3 rounded-lg text-base font-bold uppercase transition {{ request()->path() === '/' ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Beranda
                </a>
                
                <a href="/sejarah" class="block px-4 py-3 rounded-lg text-base font-bold uppercase transition {{ request()->is('sejarah*') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Sejarah
                </a>

                <a href="/pengumuman" class="block px-4 py-3 rounded-lg text-base font-bold uppercase transition {{ request()->is('pengumuman*') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-700 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Pengumuman
                </a>

                <!-- Mobile Dropdowns (Accordion) -->
                <!-- Kode Accordion Mobile tetap sama seperti sebelumnya -->
                <!-- ... -->

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
    <!-- PERUBAHAN: Border atas menggunakan border-logo-red -->
    <footer class="bg-logo-blue text-white mt-auto border-t-4 border-logo-red relative z-10">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Kontak -->
                <div>
                    <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Kontak Kami</h3>
                    <p class="text-sm leading-relaxed mb-4 text-gray-100">
                        <span class="block font-bold text-white mb-1">Gereja St. Ignatius Loyola Temanggal</span>
                        Temanggal II Rt 006 Rw 002,<br>
                        Purwomartani, Kalasan,<br>
                        Sleman DI Yogyakarta, 55571.
                    </p>
                </div>

                <!-- Tautan Cepat -->
                <div>
                    <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/" class="hover:text-logo-yellow transition">Beranda</a></li>
                        <li><a href="/sejarah" class="hover:text-logo-yellow transition">Sejarah Gereja</a></li>
                        <li><a href="/pengumuman" class="hover:text-logo-yellow transition">Jadwal Misa & Pengumuman</a></li>
                        <li><a href="/teritorial" class="hover:text-logo-yellow transition">Pembagian Wilayah</a></li>
                        <li><a href="https://gerejakalasan.org/" class="hover:text-logo-yellow transition">Paroki Maria Marganingsih Kalasan</a></li>
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
                        <textarea name="message" rows="3" required 
                            class="w-full bg-blue-800/50 border border-blue-600 text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-logo-yellow focus:outline-none placeholder-gray-300" 
                            placeholder="Tulis pesan Anda di sini..."></textarea>
                        
                        <!-- PERUBAHAN: Tombol menggunakan bg-logo-red -->
                        <button type="submit" class="bg-logo-red hover:bg-red-800 text-white text-sm font-bold py-2 px-4 rounded-md transition duration-150 w-full md:w-auto shadow-md">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-blue-800 mt-12 pt-8 text-center text-sm text-blue-200">
                &copy; {{ date('Y') }} Gereja St. Ignatius Loyola Temanggal.by @piusnando_
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>