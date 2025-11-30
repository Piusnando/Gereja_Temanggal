<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Paroki Kalasan</title>
    
    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Warna Custom sesuai Logo Gereja */
        .bg-sidebar { background-color: #1e293b; } /* Slate 800 */
        .bg-active { background-color: #003399; } /* Biru Gereja */
        .text-active { color: #ffffff; }
    </style>
</head>
<body class="bg-gray-100 antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-sidebar text-white flex flex-col shadow-xl transition-all duration-300 md:flex z-30">
        
        <!-- Sidebar Header (Brand) -->
        <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-gray-900 shadow-md shrink-0">
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-lg font-bold tracking-wide">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation Links (Scrollable Area) -->
        <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
            
            <!-- 1. DASHBOARD -->
            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- 2. LOGO & BANNER -->
            <a href="{{ route('admin.settings') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.settings') ? 'bg-active text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.settings') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-medium">Logo & Banner</span>
            </a>

            <!-- 3. PENGUMUMAN -->
            <a href="{{ route('admin.announcements.index') }}" 
            class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.announcements*') ? 'bg-active text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.announcements*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <span class="font-medium">Pengumuman</span>
            </a>

            <!-- 4. KRITIK & SARAN -->
            <a href="{{ route('admin.feedback.index') }}" 
            class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.feedback*') ? 'bg-active text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.feedback*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span class="font-medium">Kritik & Saran</span>
            </a>
            
            <!-- Separator -->
            <div class="px-4 mt-6 mb-2 text-xs font-bold text-gray-500 uppercase">Liturgi & Peribadatan</div>

            <!-- 5. DATABASE PETUGAS -->
            <a href="{{ route('admin.liturgy.personnels') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-medium">Data Petugas (All Role)</span>
            </a>

            <!-- 6. JADWAL & PENUGASAN -->
            <a href="{{ route('admin.liturgy.schedules') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-medium">Kelola Jadwal & Tugas</span>
            </a>

            <!-- MENU DATA MASTER PETUGAS -->
            <div x-data="{ openDb: true }" class="mb-2">
                <div class="px-4 py-2 text-xs font-bold text-gray-500 uppercase mt-4">Database Petugas</div>
                
                <a href="{{ route('admin.liturgy.personnels', ['type' => 'Misdinar']) }}" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-800 transition">
                    <span class="w-2 h-2 rounded-full bg-red-500 mr-3"></span> Misdinar
                </a>
                <a href="{{ route('admin.liturgy.personnels', ['type' => 'Lektor']) }}" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-800 transition">
                    <span class="w-2 h-2 rounded-full bg-green-500 mr-3"></span> Lektor
                </a>
                <a href="{{ route('admin.liturgy.personnels', ['type' => 'Mazmur']) }}" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-800 transition">
                    <span class="w-2 h-2 rounded-full bg-yellow-500 mr-3"></span> Mazmur
                </a>
                <a href="{{ route('admin.liturgy.personnels', ['type' => 'Organis']) }}" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-800 transition">
                    <span class="w-2 h-2 rounded-full bg-purple-500 mr-3"></span> Organis
                </a>
            </div>
        </nav>

        <!-- Footer Sidebar (User Profile) -->
        <div class="p-4 border-t border-gray-700 bg-gray-900 shrink-0">
            <!-- Profil User -->
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
                    <p class="text-gray-400 text-xs truncate w-32">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Tombol Action -->
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('admin.profile') }}" class="flex items-center justify-center px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg transition text-xs font-bold" title="Pengaturan Akun">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" 
                        stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Setting
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-xs font-bold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MOBILE HEADER (Muncul hanya di HP) -->
    <div class="md:hidden fixed w-full bg-sidebar text-white z-50 flex justify-between items-center p-4 shadow-md top-0">
        <span class="font-bold">Admin Panel</span>
        <button class="text-gray-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100">
        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 md:p-8 mt-14 md:mt-0">
            
            <!-- Breadcrumb / Header Kecil -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    @if(request()->routeIs('admin.settings'))
                        Pengaturan Tampilan
                    @elseif(request()->routeIs('admin.profile'))
                        Pengaturan Akun
                    @else
                        Dashboard
                    @endif
                </h2>
                <a href="/" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 flex items-center bg-white px-3 py-1 rounded shadow-sm border">
                    Lihat Website
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>

            <!-- Flash Message Sukses -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center" role="alert">
                    <div>
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">×</button>
                </div>
            @endif

            <!-- KONTEN INI YANG SEBELUMNYA HILANG KARENA STRUKTUR HTML -->
            @yield('content')
            
        </main>
    </div>

</body>
</html>