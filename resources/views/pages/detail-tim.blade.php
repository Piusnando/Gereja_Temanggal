@extends('layouts.main')

@section('title', $subName . ' - ' . $bidangName)

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">
    
    <!-- HEADER KHUSUS SUB TIM -->
    <div class="bg-logo-blue py-12 relative overflow-hidden shadow-lg border-b-4 border-logo-red">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/black-scales.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Breadcrumb / Navigasi Kecil -->
            <div class="text-blue-200 text-xs font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                <a href="{{ route('organisasi.index') }}" class="hover:text-white">Organisasi</a>
                <span>/</span>
                <a href="{{ route('organisasi.show', ['category' => $bidangName]) }}" class="hover:text-white">{{ $bidangName }}</a>
                <span>/</span>
                <span class="text-white border-b border-white">{{ $subName }}</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-wide">
                {{ $subName }}
            </h1>
            <p class="text-blue-100 mt-2 text-lg">
                Daftar anggota dan pengurus {{ $subName }}.
            </p>
        </div>
    </div>

    <!-- KONTEN ANGGOTA -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            
            <div class="flex items-center mb-8">
                <div class="w-1.5 h-8 bg-logo-red rounded-full mr-3"></div>
                <h2 class="text-2xl font-bold text-gray-800">Anggota Tim</h2>
            </div>

            <!-- Grid Anggota -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($members as $member)
                    {{-- Kita gunakan Partial yang sudah dibuat sebelumnya agar konsisten --}}
                    @include('partials.member_card', ['member' => $member])
                @endforeach
            </div>

            <!-- Tombol Kembali -->
            <div class="mt-12 pt-8 border-t border-gray-100 text-center">
                <a href="{{ route('organisasi.show', ['category' => $bidangName]) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition">
                    ← Kembali ke {{ $bidangName }}
                </a>
            </div>

        </div>
    </div>
</div>
@endsection