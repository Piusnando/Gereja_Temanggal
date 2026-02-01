@extends('layouts.admin')

@section('content')

{{-- HEADER HALAMAN --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Pemakaian Gedung</h1>
        <p class="text-sm text-gray-500">Kelola agenda penggunaan fasilitas gereja.</p>
    </div>
    
    <a href="{{ route('admin.facility-bookings.create') }}" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex items-center justify-center transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Input Jadwal Baru
    </a>
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
    <span>{{ session('success') }}</span>
    <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">&times;</button>
</div>
@endif

{{-- TABEL DATA --}}
<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left">Detail Jadwal</th>
                    
                    <!-- Kolom Fasilitas disembunyikan di Mobile -->
                    <th class="px-4 py-3 text-left hidden md:table-cell">Fasilitas</th>
                    
                    <th class="px-4 py-3 text-center w-28">Aksi</th>
                </tr>
            </thead>
            
            <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                @forelse($bookings as $item)
                <tr class="hover:bg-green-50 transition">
                    
                    <!-- KOLOM UTAMA (RESPONSIVE) -->
                    <td class="px-4 py-4 align-top">
                        <div class="flex flex-col">
                            <!-- Judul Kegiatan -->
                            <p class="font-bold text-gray-800 text-base leading-tight">
                                {{ $item->purpose }}
                            </p>

                            <!-- Peminjam -->
                            <p class="text-xs text-gray-500 mt-1">Oleh: {{ $item->booked_by }}</p>
                            
                            <!-- INFORMASI TAMBAHAN (Waktu & Fasilitas untuk Mobile) -->
                            <div class="mt-2 flex flex-wrap gap-2 items-center">
                                <!-- Badge Waktu -->
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $item->start_time->format('d M y') }}, {{ $item->start_time->format('H:i') }}
                                    @if($item->end_time)
                                        - {{ $item->end_time->format('H:i') }}
                                    @endif
                                </span>

                                <!-- Badge Fasilitas (Muncul di Mobile, tersembunyi di Desktop) -->
                                @php
                                    $badgeClass = match($item->facility_name) {
                                        'Gedung Gereja' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Teras Barat'   => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'Teras Timur'   => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'Lapangan Parkir' => 'bg-green-50 text-green-700 border-green-100',
                                        default         => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="md:hidden inline-block px-2 py-0.5 text-[10px] font-bold rounded border {{ $badgeClass }}">
                                    {{ $item->facility_name }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- KOLOM FASILITAS (Desktop Only) -->
                    <td class="px-4 py-4 hidden md:table-cell align-middle">
                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full border {{ $badgeClass }}">
                            {{ $item->facility_name }}
                        </span>
                    </td>

                    <!-- KOLOM AKSI -->
                    <td class="px-4 py-4 text-center align-middle">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Tombol Edit (Ikon) -->
                            <a href="{{ route('admin.facility-bookings.edit', $item->id) }}" 
                               class="text-blue-600 hover:text-blue-800 p-2 rounded bg-blue-50 hover:bg-blue-100 transition border border-blue-100" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>

                            <!-- Tombol Hapus (Ikon) -->
                            <form action="{{ route('admin.facility-bookings.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 p-2 rounded bg-red-50 hover:bg-red-100 transition border border-red-100" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-8 text-center text-gray-400 bg-gray-50">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Belum ada jadwal pemakaian gedung.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-4 bg-white border-t border-gray-200">
        {{ $bookings->links() }}
    </div>
</div>
@endsection