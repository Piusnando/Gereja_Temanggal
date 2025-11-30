@extends('layouts.main')
@section('title', 'Jadwal Petugas Liturgi')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <span class="text-logo-blue font-bold tracking-widest uppercase text-sm">Informasi Pelayanan</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-2">Jadwal Petugas Liturgi</h1>
            <p class="text-gray-500 mt-2">Jadwal tugas pelayanan ekaristi Gereja St. Ignatius Loyola Temanggal</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($schedules as $schedule)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-logo-red flex flex-col h-full">
                <!-- Header Jadwal -->
                <div class="bg-white p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase">
                            {{ $schedule->event_at->format('H:i') }} WIB
                        </span>
                        <span class="text-xs text-gray-400 font-bold uppercase">
                            {{ $schedule->event_at->diffForHumans() }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 leading-tight">{{ $schedule->title }}</h2>
                    <p class="text-gray-500 text-sm mt-1 font-medium">
                        {{ $schedule->event_at->translatedFormat('l, d F Y') }}
                    </p>
                </div>

                <!-- List Petugas -->
                <div class="p-5 bg-gray-50 grow">
                    <table class="w-full text-sm">
                        @foreach($schedule->assignments as $tugas)
                        <tr class="border-b border-gray-200 last:border-0">
                            <td class="py-3 font-bold text-gray-500 w-1/3 align-top">
                                {{ $tugas->role }}
                            </td>
                            <td class="py-3 align-top">
                                
                                {{-- LOGIKA PERBAIKAN DI SINI --}}
                                @if($tugas->personnel)
                                    <!-- Jika Perorangan (Ada data Personnel) -->
                                    <span class="font-bold text-gray-800 block">{{ $tugas->personnel->name }}</span>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $tugas->personnel->is_external 
                                            ? 'Luar: ' . $tugas->personnel->external_description 
                                            : ($tugas->personnel->lingkungan->name ?? '-') 
                                        }}
                                    </div>
                                
                                @elseif($tugas->lingkungan)
                                    <!-- Jika Kelompok (Ada data Lingkungan, misal Padus/Parkir) -->
                                    <span class="font-bold text-gray-800 block">{{ $tugas->lingkungan->name }}</span>
                                    <div class="text-xs text-logo-blue font-semibold mt-0.5">
                                        Tugas Wilayah / Lingkungan
                                    </div>
                                
                                @else
                                    <!-- Jika Data Hilang -->
                                    <span class="text-red-400 italic text-xs">Data petugas tidak ditemukan</span>
                                @endif

                            </td>
                        </tr>
                        @endforeach

                        @if($schedule->assignments->isEmpty())
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-400 italic text-sm">
                                Belum ada petugas yang dijadwalkan.
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            @empty
            <div class="col-span-1 md:col-span-2 text-center py-20">
                <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Tidak ada jadwal misa terdekat</h3>
                <p class="text-gray-500">Jadwal petugas liturgi belum tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection