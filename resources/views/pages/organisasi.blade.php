@extends('layouts.main')

@section('title', 'Struktur ' . $categoryName)

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Header -->
    <div class="bg-logo-blue py-12 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-white uppercase tracking-wide">
            {{ $categoryName }}
        </h1>
        <p class="text-blue-100 mt-2">Struktur Organisasi & Keanggotaan</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="bg-white rounded-xl shadow-lg p-8 min-h-[400px]">
            
            @if($members->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-lg">Belum ada data anggota untuk {{ $categoryName }}.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($members as $member)
                <div class="flex items-center p-5 border border-gray-100 rounded-xl hover:shadow-lg transition bg-white group">
                    
                    <!-- BAGIAN FOTO PROFIL -->
                    <div class="w-16 h-16 rounded-full overflow-hidden mr-4 border-2 border-gray-100 shadow-sm shrink-0 group-hover:border-blue-200 transition">
                        @if($member->image)
                            <!-- Tampilkan Foto Upload -->
                            <img src="{{ asset('storage/' . $member->image) }}" 
                                class="w-full h-full object-cover" 
                                alt="{{ $member->name }}">
                        @else
                            <!-- Tampilkan Inisial (Default) -->
                            <div class="w-full h-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl uppercase">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- DETAIL NAMA & JABATAN -->
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $member->name }}</h3>
                        
                        <span class="inline-block bg-logo-red text-white text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wider my-1">
                            {{ $member->position }}
                        </span>
                        
                        <p class="text-sm text-gray-500 flex items-center">
                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $member->lingkungan->name ?? 'Luar Lingkungan' }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
@endsection