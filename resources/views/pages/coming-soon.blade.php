@extends('layouts.main')

@section('title', $title ?? 'Segera Hadir')
@section('header', '')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center bg-gray-50 text-center px-4">
    
    <!-- Icon / Ilustrasi -->
    <div class="bg-blue-100 p-6 rounded-full mb-6 animate-bounce">
        <svg class="w-16 h-16 text-logo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
        </svg>
    </div>

    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4">
        Segera Hadir
    </h1>
    
    <p class="text-lg text-gray-600 max-w-lg mx-auto mb-8">
        Halaman <span class="font-bold text-logo-red">{{ $pageName ?? 'ini' }}</span> sedang dalam tahap penyempurnaan data dan pengembangan. Silakan kembali lagi nanti.
    </p>

    <div class="flex gap-4">
        <a href="/" class="px-6 py-3 bg-logo-blue text-white font-bold rounded-lg shadow hover:bg-blue-800 transition duration-300">
            Kembali ke Beranda
        </a>
    </div>

</div>
@endsection