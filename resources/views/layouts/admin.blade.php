<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ignatius Temanggal</title>

    <link rel="icon" href="{{ $globalLogo ?? asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-sidebar { background-color: #1e293b; }
        .bg-active { background-color: #003399; }
        
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #1e293b; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: #475569; border-radius: 20px; }
    </style>
</head>
<body class="bg-gray-100 antialiased h-screen overflow-hidden flex" x-data="{ sidebarOpen: false }">

    <!-- ============================================== -->
    <!-- MOBILE HEADER (HANYA DI LAYAR KECIL) -->
    <!-- ============================================== -->
    <header class="fixed w-full bg-sidebar text-white z-50 flex justify-between items-center p-4 shadow-md md:hidden">
        <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span class="font-bold tracking-wide">Admin Panel</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-300 focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </header>

    <!-- OVERLAY (BACKGROUND GELAP SAAT MENU BUKA) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden">
    </div>

    <!-- ============================================== -->
    <!-- SIDEBAR UTAMA (RESPONSIF) -->
    <!-- ============================================== -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-sidebar text-white transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 shadow-2xl flex flex-col">
        
        <!-- HEADER (Desktop Only) -->
        <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-gray-900 shadow-md shrink-0 md:flex">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-lg font-bold tracking-wide">Admin Panel</span>
            </div>
        </div>

        <!-- HEADER (Mobile Only - Tombol Close) -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700 bg-gray-900 md:hidden">
            <span class="font-bold text-gray-200">Menu Navigasi</span>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- NAVIGATION LINKS -->
        <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto sidebar-scroll">
            
            <!-- DASHBOARD (Semua Role) -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- ADMIN ONLY -->
            @if(Auth::user()->role == 'admin')
                <div class="px-4 mt-4 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Pengaturan Utama</div>
                
                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition {{ request()->routeIs('admin.settings') ? 'bg-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-medium">Logo & Banner</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition {{ request()->routeIs('admin.users*') ? 'bg-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium">Kelola User</span>
                </a>
            @endif

            <!-- KONTEN WEBSITE -->
            @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'omk', 'pia_pir']))
            <div class="px-4 mt-6 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Konten & Informasi</div>
            
            <!-- PENGUMUMAN -->
            <a href="{{ route('admin.announcements.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition {{ request()->routeIs('admin.announcements*') ? 'bg-active text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <span class="font-medium">Pengumuman</span>
            </a>

            <!-- BERITA KEGIATAN (BARU) -->
            <a href="{{ route('admin.activities.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition {{ request()->routeIs('admin.activities*') ? 'bg-active text-white' : '' }}">
                <!-- Icon Camera/News -->
                <svg class="w-5 h-5 mr-3 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-medium">Berita Kegiatan</span>
            </a>
            @endif

            <!-- FASILITAS (BARU) -->
            @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja']))
            <div class="px-4 mt-6 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Fasilitas Gereja</div>
            
            <a href="{{ route('admin.facility-bookings.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition {{ request()->routeIs('admin.facility-bookings*') ? 'bg-active text-white' : '' }}">
                <!-- Icon Gedung/Calendar -->
                <svg class="w-5 h-5 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-medium">Jadwal Pemakaian Gedung</span>
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'omk', 'misdinar', 'lektor', 'direktur_musik', 'pia_pir']))
            <a href="{{ route('admin.organization.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition {{ request()->routeIs('admin.organization*') ? 'bg-active text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Organisasi</span>
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja']))
            <a href="{{ route('admin.feedback.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition {{ request()->routeIs('admin.feedback*') ? 'bg-active text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span class="font-medium">Kritik & Saran</span>
            </a>
            @endif

            <!-- LITURGI -->
            @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'direktur_musik', 'misdinar', 'lektor']))
                <div class="px-4 mt-6 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Liturgi & Peribadatan</div>

                <a href="{{ route('admin.liturgy.schedules') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition group {{ request()->routeIs('admin.liturgy.schedules*') ? 'bg-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-medium">
                        {{ in_array(Auth::user()->role, ['misdinar', 'lektor']) ? 'Lihat Jadwal' : 'Kelola Jadwal' }}
                    </span>
                </a>

                <!-- DATABASE SPESIFIK (ACCORDION) -->
                <div x-data="{ openDb: {{ request()->routeIs('admin.liturgy.personnels*') ? 'true' : 'false' }} }" class="mt-4 mb-2">
                    <div class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider flex justify-between cursor-pointer hover:bg-gray-800" @click="openDb = !openDb">
                        <span>Database Petugas</span>
                        <svg :class="{'rotate-180': openDb}" class="w-3 h-3 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    
                    <div x-show="openDb" x-transition class="space-y-1">
                        @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja']))
                            <a href="{{ route('admin.liturgy.personnels') }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition"><span class="w-2 h-2 rounded-full bg-gray-500 mr-3"></span> Semua Data</a>
                            <a href="{{ route('admin.liturgy.personnels', ['type' => 'Misdinar']) }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition"><span class="w-2 h-2 rounded-full bg-red-500 mr-3"></span> Misdinar</a>
                            <a href="{{ route('admin.liturgy.personnels', ['type' => 'Lektor']) }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition"><span class="w-2 h-2 rounded-full bg-green-500 mr-3"></span> Lektor</a>
                        @endif

                        @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'direktur_musik']))
                            <a href="{{ route('admin.liturgy.personnels', ['type' => 'Mazmur']) }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition"><span class="w-2 h-2 rounded-full bg-yellow-500 mr-3"></span> Mazmur</a>
                            <a href="{{ route('admin.liturgy.personnels', ['type' => 'Organis']) }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition"><span class="w-2 h-2 rounded-full bg-purple-500 mr-3"></span> Organis</a>
                        @endif

                        @if(Auth::user()->role == 'misdinar')
                            <a href="{{ route('admin.liturgy.personnels', ['type' => 'Misdinar']) }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition"><span class="w-2 h-2 rounded-full bg-red-500 mr-3"></span> Data Misdinar</a>
                        @endif
                        @if(Auth::user()->role == 'lektor')
                            <a href="{{ route('admin.liturgy.personnels', ['type' => 'Lektor']) }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition"><span class="w-2 h-2 rounded-full bg-green-500 mr-3"></span> Data Lektor</a>
                        @endif
                    </div>
                </div>
            @endif

        </nav>

        <!-- FOOTER USER -->
        <div class="p-4 border-t border-gray-700 bg-gray-900 shrink-0">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-gray-600 overflow-hidden border-2 border-gray-500 shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="overflow-hidden">
                    <p class="text-white font-semibold text-sm truncate w-32">{{ Auth::user()->name }}</p>
                    <p class="text-gray-400 text-xs truncate w-32 uppercase">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('admin.profile') }}" class="flex items-center justify-center px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg transition text-xs font-bold shadow">
                    Setting
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-xs font-bold shadow">Logout</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100 w-full">
        <!-- Spacer untuk Header Mobile -->
        <div class="h-16 md:hidden bg-transparent"></div>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>