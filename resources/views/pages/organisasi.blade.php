@extends('layouts.main')

{{-- Menentukan Judul Halaman --}}
@php
    $pageTitle = isset($bidangName) ? $bidangName : 'Struktur Organisasi';
@endphp

@section('title', $pageTitle . ' - Gereja St. Ignatius Loyola')

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">
    
    <!-- HEADER -->
    <div class="bg-logo-blue py-16 relative overflow-hidden mb-10 shadow-lg">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white uppercase tracking-wide drop-shadow-md">
                {{ $pageTitle }}
            </h1>
            <p class="text-blue-100 mt-3 text-lg font-light">
                Struktur Pelayanan & Kepengurusan
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        {{-- KONDISI 1: TAMPILAN DETAIL BIDANG --}}
        @if(isset($bidangName) && isset($members))
            
            {{-- Loop per Sub Bidang (Tim) --}}
            @forelse($members as $subTeamName => $teamMembers)
                <section class="mb-12">
                    
                    <!-- JUDUL TIM (Pemisah) -->
                    <div class="flex items-center mb-6">
                        <div class="w-1.5 h-8 bg-logo-red rounded-full mr-3"></div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 uppercase tracking-tight">
                            {{ $subTeamName }}
                        </h2>
                        <div class="ml-4 grow h-px bg-gray-200"></div> 
                    </div>

                    <!-- GRID ANGGOTA UNTUK TIM INI -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($teamMembers as $member)
                            @include('partials.member_card', ['member' => $member])
                        @endforeach
                    </div>

                </section>
            @empty
                <div class="text-center py-20 bg-white rounded-xl shadow border border-gray-100">
                    <p class="text-lg text-gray-500 font-medium">Belum ada data anggota untuk bidang ini.</p>
                </div>
            @endforelse

        {{-- KONDISI 2: TAMPILAN INDEX UTAMA (Ada variabel $groupedMembers dan $categoriesOrder) --}}
        @elseif(isset($categoriesOrder) && isset($groupedMembers))
            
            @foreach($categoriesOrder as $bidang)
                @php $listMembers = $groupedMembers->get($bidang); @endphp
                
                @if($listMembers && $listMembers->count() > 0)
                <section>
                    <div class="flex items-center mb-6">
                        <div class="w-1.5 h-8 bg-logo-blue rounded-full mr-3"></div>
                        <a href="{{ route('organisasi.show', ['category' => $bidang]) }}" class="text-2xl md:text-3xl font-bold text-gray-800 uppercase tracking-tight hover:text-logo-blue transition">
                            {{ $bidang }} &rarr;
                        </a>
                        <div class="ml-4 grow h-px bg-gray-200"></div> 
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($listMembers->take(3) as $member) {{-- Tampilkan preview 3 orang saja --}}
                            @include('partials.member_card', ['member' => $member])
                        @endforeach
                    </div>
                    
                    @if($listMembers->count() > 3)
                    <div class="mt-4 text-center">
                        <a href="{{ route('organisasi.show', ['category' => $bidang]) }}" class="text-sm font-bold text-logo-red hover:underline">
                            Lihat Selengkapnya ({{ $listMembers->count() }} Anggota)
                        </a>
                    </div>
                    @endif
                </section>
                @endif
            @endforeach

        @endif

    </div>
</div>
@endsection