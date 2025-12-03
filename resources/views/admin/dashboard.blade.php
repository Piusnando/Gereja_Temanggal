@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    
    <!-- WELCOME BANNER -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-8 border-logo-blue relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
                Selamat Datang, <span class="text-logo-blue">{{ Auth::user()->name }}</span>!
            </h1>
            <p class="text-gray-600 text-lg">
                Status Akun: 
                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wide border border-blue-200">
                    {{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}
                </span>
            </p>
            <p class="mt-4 text-sm text-gray-500">
                Selamat bertugas melayani di Gereja St. Ignatius Loyola Temanggal.
            </p>
        </div>
        
        <!-- Decoration Icon Background -->
        <div class="absolute right-0 top-0 h-full w-48 bg-linear-to-l from-blue-50 to-transparent opacity-50 pointer-events-none"></div>
        <svg class="absolute right-10 top-1/2 transform -translate-y-1/2 w-32 h-32 text-blue-100" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5 10 5 10-5-5-2.5-5 2.5z"/></svg>
    </div>

    <!-- SHORTCUT MENU GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- 1. KELOLA TAMPILAN & USER (Hanya Admin) --}}
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('admin.settings') }}" class="group block bg-white rounded-xl shadow-md p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-yellow-100 text-yellow-600 p-3 rounded-lg group-hover:bg-yellow-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Sistem</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-yellow-600 transition">Logo & Banner</h3>
                <p class="text-sm text-gray-500 mt-2">Atur tampilan beranda website.</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="group block bg-white rounded-xl shadow-md p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-100 text-gray-600 p-3 rounded-lg group-hover:bg-gray-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Pengguna</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-gray-600 transition">Kelola User</h3>
                <p class="text-sm text-gray-500 mt-2">Tambah atau edit akun pengurus.</p>
            </a>
        @endif

        {{-- 2. PENGUMUMAN (Admin, Pengurus, OMK, PIA/PIR) --}}
        @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'omk', 'pia_pir']))
            <a href="{{ route('admin.announcements.index') }}" class="group block bg-white rounded-xl shadow-md p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Informasi</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition">Pengumuman</h3>
                <p class="text-sm text-gray-500 mt-2">Buat pengumuman kegiatan.</p>
            </a>
        @endif

        {{-- 3. ORGANISASI (Hampir Semua Role) --}}
        @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'omk', 'misdinar', 'lektor', 'direktur_musik', 'pia_pir']))
            <a href="{{ route('admin.organization.index') }}" class="group block bg-white rounded-xl shadow-md p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-pink-100 text-pink-600 p-3 rounded-lg group-hover:bg-pink-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Organisasi</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-pink-600 transition">Struktur Anggota</h3>
                <p class="text-sm text-gray-500 mt-2">Update data anggota {{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}.</p>
            </a>
        @endif

        {{-- 4. KRITIK & SARAN (Hanya Admin & Pengurus) --}}
        @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja']))
            <a href="{{ route('admin.feedback.index') }}" class="group block bg-white rounded-xl shadow-md p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 text-green-600 p-3 rounded-lg group-hover:bg-green-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Umat</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-green-600 transition">Kritik & Saran</h3>
                <p class="text-sm text-gray-500 mt-2">Lihat masukan dari umat.</p>
            </a>
        @endif

        {{-- 5. JADWAL LITURGI (Admin, Pengurus, Dir Musik) --}}
        @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'direktur_musik']))
            <a href="{{ route('admin.liturgy.schedules') }}" class="group block bg-white rounded-xl shadow-md p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Jadwal</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-purple-600 transition">Kelola Jadwal Misa</h3>
                <p class="text-sm text-gray-500 mt-2">Atur jadwal dan penugasan petugas.</p>
            </a>
        @endif

        {{-- 6. INFO USER BIASA (Misdinar, Lektor) --}}
        @if(in_array(Auth::user()->role, ['misdinar', 'lektor']))
            <div class="group block bg-white rounded-xl shadow-md p-6 border border-gray-100 opacity-75">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-100 text-gray-500 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Profil</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Profil Saya</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Halo petugas! Anda bisa melihat jadwal dan rekan petugas di menu sebelah kiri.
                </p>
            </div>
        @endif

    </div>

    <!-- UPDATE PASSWORD CARD -->
    <div class="mt-8 bg-gray-800 rounded-xl shadow-lg p-6 text-white flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Keamanan Akun</h3>
            <p class="text-gray-300 text-sm mt-1">Ganti password secara berkala untuk keamanan.</p>
        </div>
        <a href="{{ route('admin.profile') }}" class="bg-white text-gray-900 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-200 transition">
            Ganti Password
        </a>
    </div>

</div>
@endsection