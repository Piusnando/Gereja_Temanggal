@extends('layouts.main')

@section('title', 'Jadwal Pemakaian Gedung - Gereja St. Ignatius Loyola')
<meta name="description" content="@yield('meta_description', 'Website resmi Gereja St. Ignatius Loyola Kalasan Tengah - Paroki Maria Marganingsih Kalasan. Informasi jadwal misa, pengumuman, dan teritorial wilayah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gereja St. Ignatius Loyola, Kalasan Tengah, Gereja Temanggal, Paroki Kalasan, Gereja Katolik, gereja di Sleman, Jadwal Misa, Pengumuman Gereja, Teritorial Wilayah, Organisasi Gereja, Petugas Liturgi, OMK, Misdinar, Lektor, Mazmur, Paduan Suara, Parkir Gereja, kalasan tengah, gereja yogyakarta, 
    gereja sleman, gereja di kalasan, paroki maria marganingsih kalasan, Gereja St. Ignatius Loyola Temanggal, Gereja Katolik di Kalasan, Jadwal Misa Kalasan, Pengumuman Gereja Kalasan, Teritorial Wilayah Kalasan, Organisasi Gereja Kalasan, Petugas Liturgi Kalasan, OMK Kalasan, Misdinar Kalasan, Lektor Kalasan, Mazmur Kalasan, Paduan Suara Kalasan, Parkir Gereja Kalasan,
    gereja temanggal, gereja di temanggal, paroki kalasan, Gereja St. Ignatius Loyola Kalasan Tengah Temanggal ')">

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
            
            <!-- Legend / Keterangan Warna (DISESUAIKAN DENGAN ADMIN) -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-4 text-xs font-bold text-gray-600 uppercase tracking-wide">
                <div class="flex items-center"><span class="w-3 h-3 bg-blue-100 border border-blue-300 rounded-full mr-2"></span> Gedung Gereja</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-orange-100 border border-orange-300 rounded-full mr-2"></span> Teras Barat</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-purple-100 border border-purple-300 rounded-full mr-2"></span> Teras Timur</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-green-100 border border-green-300 rounded-full mr-2"></span> Lapangan Parkir</div>
            </div>

            <!-- Tabel Jadwal -->
            <div class="overflow-x-auto">
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
                            <td class="p-5">
                                <div class="flex flex-col">
                                    <span class="text-2xl font-black text-gray-800">{{ $booking->start_time->format('d') }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $booking->start_time->translatedFormat('F Y') }}</span>
                                    <span class="text-xs text-logo-blue font-medium mt-1">{{ $booking->start_time->translatedFormat('l') }}</span>
                                </div>
                            </td>

                            <!-- Waktu -->
                            <td class="p-5 align-middle">
                                <div class="inline-flex flex-col bg-gray-100 px-3 py-1.5 rounded text-gray-700 font-mono text-xs text-center border border-gray-200">
                                    <span>{{ $booking->start_time->format('H:i') }}</span>
                                    
                                    {{-- PERUBAHAN DI SINI: Tampilkan hanya jika ada end_time --}}
                                    @if($booking->end_time)
                                    <span class="text-gray-400 text-[10px]">s/d</span>
                                    <span>{{ $booking->end_time->format('H:i') }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Lokasi (Badge Warna - DISESUAIKAN DENGAN ADMIN) -->
                            <td class="p-5 align-middle">
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
                            <td class="p-5 align-middle">
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

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>

        </div>
    </div>
</div>
@endsection