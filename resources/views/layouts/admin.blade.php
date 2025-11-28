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
    <!-- Hidden on mobile, fixed on desktop -->
    <aside class="w-64 bg-sidebar text-white flex flex-col shadow-xl transition-all duration-300 md:flex z-30">
        
        <!-- Sidebar Header (Brand) -->
        <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-gray-900 shadow-md">
            <div class="flex items-center gap-2">
                <!-- Icon Admin Sederhana -->
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-lg font-bold tracking-wide">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
            
            <!-- 1. DASHBOARD (Placeholder jika nanti ada) -->
            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- 2. LOGO & BANNER (Active State Logic) -->
            <!-- Kita cek apakah route saat ini adalah 'admin.settings', jika ya pakai class active -->
            <a href="{{ route('admin.settings') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.settings') ? 'bg-active text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.settings') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-medium">Logo & Banner</span>
            </a>

            <!-- 3. PENGUMUMAN -->
            <a href="{{ route('admin.announcements.index') }}" 
            class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.announcements*') ? 'bg-active text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <!-- Icon Kertas/News -->
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.announcements*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <span class="font-medium">Pengumuman</span>
            </a>
            
            <!-- Tambahkan menu lain di sini nanti -->
        </nav>

        <!-- Footer Sidebar (Logout) -->
        <div class="p-4 border-t border-gray-700 bg-gray-900">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="text-sm">
                    <p class="text-white font-semibold">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-gray-400 text-xs">Pengurus</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE HEADER (Muncul hanya di HP) -->
    <div class="md:hidden fixed w-full bg-sidebar text-white z-50 flex justify-between items-center p-4 shadow-md">
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

            <!-- Konten Halaman -->
            @yield('content')
            
        </main>
    </div>

</body>
</html>