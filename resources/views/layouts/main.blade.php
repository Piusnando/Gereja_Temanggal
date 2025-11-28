<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        .text-logo-blue { color: #003399; }
        .bg-logo-blue { background-color: #003399; }
        .text-logo-red { color: #DC2626; }
        .bg-logo-red { background-color: #DC2626; }
        .text-logo-yellow { color: #FFCC00; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 relative z-50 shadow-sm transition-all duration-300" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24">
                
                <!-- HEADER KIRI: Logo & Nama -->
                <div class="flex items-center gap-4">
                    <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" 
                         alt="Logo Gereja" 
                         class="h-14 w-auto object-contain drop-shadow-sm hover:scale-105 transition duration-300">
                    
                    <div class="flex flex-col justify-center">
                        <a href="/" class="text-lg md:text-xl font-extrabold text-logo-blue leading-tight hover:opacity-80 transition uppercase tracking-wide">
                            Gereja St. Ignatius Loyola<br class="hidden md:block"> 
                            <span class="text-logo-red">Kalasan Tengah</span>
                        </a>
                        <span class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-widest mt-0.5 uppercase">
                            Paroki Maria Marganingsih Kalasan
                        </span>
                    </div>
                </div>

                <!-- HEADER KANAN: Menu Desktop -->
                <div class="hidden md:flex md:items-center md:gap-x-8">
                    
                    <!-- 1. BERANDA -->
                    <a href="/" 
                       class="h-full flex items-center px-1 pt-1 text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 
                       {{ request()->path() === '/' ? 'text-logo-red border-logo-red' : 'text-gray-500 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Beranda
                    </a>

                    <!-- 2. SEJARAH -->
                    <a href="/sejarah" 
                       class="h-full flex items-center px-1 pt-1 text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 
                       {{ request()->is('sejarah*') ? 'text-logo-red border-logo-red' : 'text-gray-500 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Sejarah
                    </a>

                    <!-- 3. PENGUMUMAN -->
                    <a href="/pengumuman" 
                       class="h-full flex items-center px-1 pt-1 text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 
                       {{ request()->is('pengumuman*') ? 'text-logo-red border-logo-red' : 'text-gray-500 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Pengumuman
                    </a>

                    <!-- 4. TERITORIAL (DROPDOWN) -->
                    <div class="relative h-full flex items-center group" x-data="{ dropdownOpen: false }">
                        <button @mouseenter="dropdownOpen = true" @mouseleave="dropdownOpen = false"
                                class="h-full flex items-center px-1 pt-1 text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 focus:outline-none
                                {{ request()->is('teritorial*') ? 'text-logo-red border-logo-red' : 'text-gray-500 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                            Teritorial
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Isi Dropdown -->
                        <div x-show="dropdownOpen" 
                             @mouseenter="dropdownOpen = true" 
                             @mouseleave="dropdownOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute top-[80%] left-0 w-64 bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden z-50">
                             
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

                    <!-- 5. ORGANISASI -->
                    <a href="/organisasi" 
                       class="h-full flex items-center px-1 pt-1 text-sm font-bold tracking-wider uppercase border-b-4 transition-all duration-300 
                       {{ request()->is('organisasi*') ? 'text-logo-red border-logo-red' : 'text-gray-500 border-transparent hover:text-logo-blue hover:border-blue-200' }}">
                        Organisasi
                    </a>

                </div>

                <!-- Mobile Menu Button -->
                <div class="-mr-2 flex items-center md:hidden">
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
        <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-200 shadow-xl absolute w-full left-0 z-50 overflow-y-auto max-h-[80vh]">
            <div class="pt-2 pb-4 space-y-1 px-4">
                
                <a href="/" class="block px-4 py-3 rounded-lg text-base font-bold uppercase transition {{ request()->path() === '/' ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-600 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Beranda
                </a>
                
                <a href="/sejarah" class="block px-4 py-3 rounded-lg text-base font-bold uppercase transition {{ request()->is('sejarah*') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-600 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Sejarah
                </a>

                <a href="/pengumuman" class="block px-4 py-3 rounded-lg text-base font-bold uppercase transition {{ request()->is('pengumuman*') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-600 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Pengumuman
                </a>

                <!-- Mobile Teritorial (List ke bawah) -->
                <div class="border-t border-b border-gray-100 py-2">
                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Wilayah Teritorial</div>
                    @if(isset($globalTerritories))
                        @foreach($globalTerritories as $wilayah)
                        <a href="{{ route('teritorial.show', $wilayah->slug) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-logo-blue hover:bg-blue-50 ml-2 border-l-2 border-gray-200">
                            {{ $wilayah->name }}
                        </a>
                        @endforeach
                    @endif
                </div>

                <a href="/organisasi" class="block px-4 py-3 rounded-lg text-base font-bold uppercase transition {{ request()->is('organisasi*') ? 'bg-red-50 text-logo-red border-l-4 border-logo-red' : 'text-gray-600 hover:bg-gray-50 hover:text-logo-blue' }}">
                    Organisasi
                </a>

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

        <!-- Hapus container bawaan disini agar banner bisa full width di halaman home -->
        <!-- Content spesifik halaman akan mengatur containernya sendiri jika perlu -->
        @yield('content')
        
    </main>

    <!-- Footer -->
    <footer class="bg-logo-blue text-white mt-auto border-t-4 border-logo-red">
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
                    </ul>
                </div>

                <!-- Kritik Saran -->
                <div>
                    <h3 class="text-logo-yellow text-lg font-bold mb-4 uppercase tracking-wider">Kritik & Saran</h3>
                    <p class="text-sm text-gray-100 mb-3">
                        Masukan Anda sangat berarti bagi perkembangan pelayanan gereja kami.
                    </p>

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
                        
                        <button type="submit" class="bg-logo-red hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded-md transition duration-150 w-full md:w-auto shadow-md">
                            Kirim Pesan
                        </button>
                    </form>
                </div>

            </div>
            
            <div class="border-t border-blue-800 mt-12 pt-8 text-center text-sm text-blue-200">
                &copy; {{ date('Y') }} Gereja St. Ignatius Loyola Temanggal. AMDG.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>