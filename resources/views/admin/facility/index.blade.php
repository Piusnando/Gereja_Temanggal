@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Jadwal Pemakaian Gedung</h1>
    <a href="{{ route('admin.facility-bookings.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
        + Input Jadwal Baru
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
            <tr>
                <th class="px-5 py-3 text-left">Waktu Penggunaan</th>
                <th class="px-5 py-3 text-left">Fasilitas</th>
                <th class="px-5 py-3 text-left">Peminjam / Kegiatan</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 text-sm">
            @forelse($bookings as $item)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="font-bold text-gray-800">{{ $item->start_time->format('d M Y') }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $item->start_time->format('H:i') }} - {{ $item->end_time->format('H:i') }} WIB
                    </div>
                </td>
                <td class="px-5 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full 
                        {{ $item->facility_name == 'Gereja Utama' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $item->facility_name }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="font-semibold">{{ $item->purpose }}</div>
                    <div class="text-xs text-gray-500">Oleh: {{ $item->booked_by }}</div>
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <!-- Tombol Edit -->
                        <a href="{{ route('admin.facility-bookings.edit', $item->id) }}" 
                           class="text-blue-600 hover:text-blue-800 font-bold text-xs border border-blue-200 px-3 py-1 rounded bg-blue-50 hover:bg-blue-100 transition">
                            Edit
                        </a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('admin.facility-bookings.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-bold text-xs border border-red-200 px-3 py-1 rounded bg-red-50 hover:bg-red-100 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="p-8 text-center text-gray-400">Belum ada jadwal pemakaian gedung.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $bookings->links() }}</div>
</div>
@endsection