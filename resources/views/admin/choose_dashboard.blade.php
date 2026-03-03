<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Akses - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4">

    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-blue-900 mb-2">Selamat Datang, Admin</h1>
        <p class="text-gray-600">Silakan pilih ruang kerja yang ingin Anda akses saat ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-4xl">
        
        <!-- Pilihan 1: Dashboard Utama -->
        <a href="{{ route('dashboard') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 p-8 border-t-8 border-blue-600 flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white text-blue-600 transition duration-300">
                <!-- Icon Gereja/Paroki -->
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Utama</h2>
            <p class="text-gray-500 text-sm">Kelola kegiatan, pengumuman, user, liturgi, dan data teritorial paroki.</p>
            <span class="mt-6 text-blue-600 font-bold group-hover:text-blue-800 flex items-center">Masuk Ruang Utama <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></span>
        </a>

        <!-- Pilihan 2: Dashboard Inventaris -->
        <a href="{{ route('admin.inventaris.dashboard') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 p-8 border-t-8 border-green-600 flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white text-green-600 transition duration-300">
                <!-- Icon Box/Inventaris -->
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Manajemen Inventaris</h2>
            <p class="text-gray-500 text-sm">Kelola aset gereja, peminjaman barang, dan stok peralatan.</p>
            <span class="mt-6 text-green-600 font-bold group-hover:text-green-800 flex items-center">Masuk Ruang Inventaris <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></span>
        </a>

    </div>

    <!-- Tombol Logout & Setting (Opsional) -->
    <div class="mt-12 flex gap-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-500 hover:text-red-600 text-sm font-semibold transition">← Logout Keluar</button>
        </form>
    </div>

</body>
</html>