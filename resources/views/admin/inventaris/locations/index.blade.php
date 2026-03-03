@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Master Data Lokasi</h1>
        <p class="text-sm text-gray-500">Kelola lokasi penyimpanan barang inventaris.</p>
    </div>
    <a href="{{ route('admin.inventaris.locations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
        + Tambah Lokasi
    </a>
</div>

@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between">
    <span>{{ session('success') }}</span>
    <button @click="show = false" class="text-green-700 font-bold">&times;</button>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex justify-between">
    <span>{{ session('error') }}</span>
    <button @click="show = false" class="text-red-700 font-bold">&times;</button>
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
            <tr>
                <th class="px-5 py-3 text-left">Nama Lokasi</th>
                <th class="px-5 py-3 text-left">Kode (4 Digit)</th>
                <th class="px-5 py-3 text-center">Jumlah Barang</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
            @forelse($locations as $location)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4 font-bold">
                    {{ $location->name }}
                </td>
                <td class="px-5 py-4">
                    <span class="bg-gray-100 text-gray-600 font-mono px-2 py-1 rounded border border-gray-300 font-bold">
                        {{ $location->code }}
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                        {{ $location->items->count() }} Item
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.inventaris.locations.edit', $location->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 p-2 rounded hover:bg-blue-100 transition border border-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.inventaris.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Yakin hapus lokasi ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 p-2 rounded hover:bg-red-100 transition border border-red-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-8 text-center text-gray-400">
                    Belum ada data lokasi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 bg-gray-50 border-t border-gray-100">
        {{ $locations->links() }}
    </div>
</div>
@endsection