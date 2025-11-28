<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gereja St. Ignatius Loyola')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
    <!-- PERUBAHAN DISINI: -->
    <!-- Menghapus 'sticky top-0'. Diganti 'relative' agar z-index tetap jalan tapi tidak nempel -->
    <nav class="bg-white border-b border-gray-200 relative z-50 shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24">
                
                <!-- HEADER KIRI: Logo & Nama -->
                <div class="flex items-center space-x-3">
                    
                    <!-- Logo Global dari AppServiceProvider -->
                    <img src="{{ $globalLogo ?? asset('images/logo-default.png') }}" 
                         alt="Logo Gereja" 
                         class="h-16 w-auto object-contain">
                    
                    <div class="flex flex-col justify-center">
                        <a href="/" class="text-lg md:text-xl font-bold text-logo-blue leading-tight hover:opacity-80 transition uppercase tracking-wide">
                            Gereja St. Ignatius Loyola<br class="hidden md:block"> <span class="text-logo-red">Kalasan Tengah</span>
                        </a>
                        <span class="text-xs md:text-sm text-gray-600 font-medium mt-0.5">
                            Paroki Maria Marganingsih Kalasan
                        </span>
                    </div>
                </div>

                <!-- HEADER KANAN: Menu Desktop -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    @foreach([
                        '/' => 'Beranda',
                        'sejarah' => 'Sejarah',
                        'pengumuman' => 'Pengumuman',
                        'teritorial' => 'Teritorial',
                        'organisasi' => 'Organisasi'
                    ] as $route => $label)
                        <a href="{{ url($route) }}" 
                           class="{{ request()->is(ltrim($route, '/')) ? 'text-logo-red border-logo-red' : 'text-gray-500 border-transparent hover:text-logo-blue hover:border-blue-300' }} border-b-2 px-1 pt-1 text-sm font-bold uppercase transition duration-150 ease-in-out h-full flex items-center">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <!-- Mobile Menu Button -->
                <div class="-mr-2 flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-200 shadow-lg absolute w-full left-0 z-50">
            <div class="pt-2 pb-3 space-y-1 px-2">
                @foreach([
                    '/' => 'Beranda',
                    'sejarah' => 'Sejarah',
                    'pengumuman' => 'Pengumuman',
                    'teritorial' => 'Teritorial',
                    'organisasi' => 'Organisasi'
                ] as $route => $label)
                    <a href="{{ url($route) }}" class="block px-3 py-2 rounded-md text-base font-bold uppercase {{ request()->is(ltrim($route, '/')) ? 'bg-blue-50 text-logo-red' : 'text-gray-700 hover:text-logo-blue hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
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

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
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
                    <form action="#" method="POST" class="space-y-2">
                        @csrf
                        <textarea name="message" rows="3" class="w-full bg-blue-800/50 border border-blue-600 text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-logo-yellow focus:outline-none placeholder-gray-300" placeholder="Tulis pesan Anda..."></textarea>
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