@extends('layouts.main')

@section('title', 'Jadwal Pemakaian Gedung - Gereja St. Ignatius Loyola')

@section('content')
<div class="min-h-screen bg-gray-50 pb-16">
    
    <!-- HEADER -->
    <div class="bg-logo-blue pt-16 pb-24 relative overflow-hidden shadow-lg">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/black-scales.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Jadwal Pemakaian Fasilitas
            </h1>
            <p class="text-blue-100 text-lg md:text-xl font-medium max-w-3xl mx-auto leading-relaxed">
                Informasi ketersediaan dan agenda penggunaan fasilitas gereja.
            </p>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            
            

            <!-- TAMPILAN DESKTOP (TABEL) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white text-gray-500 uppercase text-xs font-bold border-b-2 border-gray-100">
                        <tr>
                            <th class="p-5 w-40">Tanggal</th>
                            <th class="p-5 w-32">Waktu</th>
                            <th class="p-5 w-48">Lokasi</th>
                            <th class="p-5">Kegiatan / Peminjam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-blue-50/30 transition duration-150">
                            <!-- Tanggal -->
                            <td class="p-5 align-top">
                                <div class="flex flex-col">
                                    <span class="text-2xl font-black text-gray-800">{{ $booking->start_time->format('d') }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $booking->start_time->translatedFormat('F Y') }}</span>
                                    <span class="text-xs text-logo-blue font-medium mt-1">{{ $booking->start_time->translatedFormat('l') }}</span>
                                </div>
                            </td>

                            <!-- Waktu -->
                            <td class="p-5 align-top">
                                <div class="inline-flex flex-col bg-gray-100 px-3 py-1.5 rounded text-gray-700 font-mono text-xs text-center border border-gray-200">
                                    <span>{{ $booking->start_time->format('H:i') }}</span>
                                    @if($booking->end_time)
                                    <span class="text-gray-400 text-[10px]">s/d</span>
                                    <span>{{ $booking->end_time->format('H:i') }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Lokasi (Badge Warna) -->
                            <td class="p-5 align-top">
                                @php
                                    $badge = match($booking->facility_name) {
                                        'Gedung Gereja'   => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'Teras Barat'     => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'Teras Timur'     => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'Lapangan Parkir' => 'bg-green-100 text-green-800 border-green-200',
                                        default           => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $badge }}">
                                    {{ $booking->facility_name }}
                                </span>
                            </td>

                            <!-- Detail -->
                            <td class="p-5 align-top">
                                <h3 class="font-bold text-gray-900 text-base mb-1">{{ $booking->purpose }}</h3>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Peminjam: <span class="font-semibold ml-1 text-gray-700">{{ $booking->booked_by }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-gray-400 bg-gray-50 italic">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Tidak ada jadwal pemakaian gedung yang akan datang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Legend / Keterangan Warna -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 text-xs font-bold text-gray-600 uppercase tracking-wide justify-center md:justify-start">
                <div class="flex items-center"><span class="w-3 h-3 bg-blue-100 border border-blue-300 rounded-full mr-2"></span> Gereja</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-orange-100 border border-orange-300 rounded-full mr-2"></span> Teras Barat</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-purple-100 border border-purple-300 rounded-full mr-2"></span> Teras Timur</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-green-100 border border-green-300 rounded-full mr-2"></span> Parkir</div>
            </div>

            <!-- TAMPILAN MOBILE (CARD STACK) -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <div class="p-5 bg-white hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start mb-3">
                        <!-- Tanggal & Waktu -->
                        <div class="flex items-center gap-3">
                            <div class="text-center bg-gray-100 px-3 py-1 rounded-lg border border-gray-200 min-w-[50px]">
                                <span class="block text-xl font-black text-gray-800 leading-none">{{ $booking->start_time->format('d') }}</span>
                                <span class="block text-[10px] font-bold text-gray-500 uppercase">{{ $booking->start_time->format('M') }}</span>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-800">{{ $booking->start_time->translatedFormat('l') }}</span>
                                <span class="text-xs text-gray-500 font-mono">
                                    {{ $booking->start_time->format('H:i') }}
                                    @if($booking->end_time) - {{ $booking->end_time->format('H:i') }} @endif
                                    WIB
                                </span>
                            </div>
                        </div>

                        <!-- Badge Lokasi Mobile (Kecil) -->
                        @php
                            $badgeMobile = match($booking->facility_name) {
                                'Gedung Gereja'   => 'bg-blue-50 text-blue-700 border-blue-100',
                                'Teras Barat'     => 'bg-orange-50 text-orange-700 border-orange-100',
                                'Teras Timur'     => 'bg-purple-50 text-purple-700 border-purple-100',
                                'Lapangan Parkir' => 'bg-green-50 text-green-700 border-green-100',
                                default           => 'bg-gray-50 text-gray-700 border-gray-100'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase border {{ $badgeMobile }}">
                            {{ $booking->facility_name }}
                        </span>
                    </div>

                    <!-- Detail Kegiatan -->
                    <div>
                        <h3 class="font-bold text-gray-900 text-base leading-snug mb-1">{{ $booking->purpose }}</h3>
                        <p class="text-xs text-gray-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ $booking->booked_by }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400 italic">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tidak ada jadwal.
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $bookings->links() }}
            </div>

        </div>
    </div>
</div>
@endsection