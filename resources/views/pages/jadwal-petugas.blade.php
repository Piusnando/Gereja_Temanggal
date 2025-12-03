@extends('layouts.main')

@section('title', 'Jadwal Petugas Liturgi - Gereja St. Ignatius Loyola')
@section('header', '')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER TITLE -->
        <div class="text-center mb-12">
            <span class="text-logo-blue font-bold tracking-widest uppercase text-sm">Informasi Pelayanan</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-2">Jadwal Petugas Liturgi</h1>
            <p class="text-gray-500 mt-2">Jadwal tugas pelayanan ekaristi Gereja St. Ignatius Loyola Temanggal</p>
        </div>

        <!-- GRID JADWAL -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            @forelse($schedules as $schedule)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-logo-red flex flex-col h-full hover:shadow-xl transition duration-300">
                
                <!-- Header Card: Tanggal & Judul -->
                <div class="bg-white p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            {{ $schedule->event_at->format('H:i') }} WIB
                        </span>
                        <div class="flex items-center text-xs text-gray-500 font-bold uppercase">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $schedule->event_at->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-800 leading-tight">
                        {{ $schedule->title }}
                    </h2>
                    <p class="text-sm text-gray-400 mt-1 italic">
                        {{ $schedule->event_at->diffForHumans() }}
                    </p>
                </div>

                <!-- Body Card: Daftar Petugas -->
                <div class="p-0 bg-gray-50 grow">
                    <table class="w-full text-sm text-left">
                        <tbody class="divide-y divide-gray-200">
                            @foreach($schedule->assignments as $tugas)
                            <tr class="group hover:bg-white transition">
                                <!-- Kolom Peran -->
                                <td class="py-4 px-6 font-bold text-gray-500 w-1/3 align-top uppercase text-xs tracking-wider">
                                    {{ $tugas->role }}
                                </td>
                                
                                <!-- Kolom Nama Petugas -->
                                <td class="py-4 px-6 align-top">
                                    
                                    {{-- 1. JIKA PETUGAS PERORANGAN (Misdinar, Lektor, Mazmur, Organis) --}}
                                    @if($tugas->personnel)
                                        <div class="flex items-start">
                                            <span class="text-gray-800 font-bold text-base leading-snug">
                                                {{ $tugas->personnel->name }}
                                            </span>
                                        </div>
                                        
                                        <!-- Info Asal -->
                                        <div class="mt-1">
                                            @if($tugas->personnel->is_external)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    {{ $tugas->personnel->external_description }} (Luar)
                                                </span>
                                            @else
                                                <span class="text-gray-500 text-xs flex items-center">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $tugas->personnel->lingkungan->name ?? '-' }}
                                                </span>
                                            @endif
                                        </div>

                                    {{-- 2. JIKA TUGAS KELOMPOK INTERNAL (Padus/Parkir Wilayah) --}}
                                    @elseif($tugas->lingkungan)
                                        <div class="flex items-start">
                                            <span class="text-gray-800 font-bold text-base leading-snug">
                                                {{ $tugas->lingkungan->name }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Tugas Wilayah
                                            </span>
                                        </div>

                                    {{-- 3. JIKA TUGAS KELOMPOK EKSTERNAL (Padus Tamu/Karang Taruna) --}}
                                    @elseif($tugas->description)
                                        <div class="flex items-start">
                                            <span class="text-gray-800 font-bold text-base leading-snug">
                                                {{ $tugas->description }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                Dari Luar Paroki
                                            </span>
                                        </div>

                                    {{-- 4. JIKA DATA HILANG --}}
                                    @else
                                        <span class="text-red-400 italic text-xs">Data petugas tidak ditemukan</span>
                                    @endif

                                </td>
                            </tr>
                            @endforeach

                            @if($schedule->assignments->isEmpty())
                            <tr>
                                <td colspan="2" class="py-8 text-center text-gray-400 italic text-sm">
                                    Belum ada petugas yang dijadwalkan untuk misa ini.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="col-span-1 md:col-span-2 text-center py-20 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900">Tidak ada jadwal misa terdekat</h3>
                <p class="text-gray-500 mt-2">Jadwal petugas liturgi belum tersedia saat ini. Silakan cek kembali nanti.</p>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection