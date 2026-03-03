@extends('layouts.admin')

@section('title', 'Dashboard Inventaris')

@section('content')
<div class="container mx-auto px-4 py-2">
    
    <!-- HEADER & TOMBOL BERALIH -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="text-center md:text-left">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center justify-center md:justify-start">
                <svg class="w-7 h-7 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Dashboard Inventaris
            </h1>
            <p class="text-sm text-gray-500 mt-1">Sistem manajemen aset, barang, dan peminjaman Gereja.</p>
        </div>

        <!-- TOMBOL BERALIH KE UTAMA (HANYA UNTUK ADMIN) -->
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-bold py-2.5 px-5 rounded-lg shadow-sm transition duration-300 border border-blue-200 hover:border-transparent">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Beralih ke Dashboard Utama
            </a>
        @endif
    </div>

    <!-- AREA KONTEN INVENTARIS NANTI DI SINI -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Contoh Kartu Statistik Kosong (Bisa dihapus nanti) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-t-4">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Aset Barang</p>
            <h2 class="text-3xl font-black text-gray-800 mt-2">0</h2>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-t-4">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Barang Rusak</p>
            <h2 class="text-3xl font-black text-gray-800 mt-2">0</h2>
        </div>
    </div>

</div>
@endsection