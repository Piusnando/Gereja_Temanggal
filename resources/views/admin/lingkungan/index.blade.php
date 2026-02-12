@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Data Lingkungan</h1>
    <a href="{{ route('admin.lingkungan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Tambah Lingkungan
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-5 py-3 text-left">Wilayah</th>
                    <th class="px-5 py-3 text-left">Nama Lingkungan</th>
                    <th class="px-5 py-3 text-left">Ketua & Pelindung</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                @forelse($lingkungans as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 font-bold text-blue-600">
                        {{ $item->territory->name }}
                    </td>
                    <td class="px-5 py-4 font-semibold">
                        {{ $item->name }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <!-- Thumbnail Foto Santo -->
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden shrink-0">
                                @if($item->saint_image)
                                    <img src="{{ asset('storage/' . $item->saint_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-xs text-gray-500">?</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Ketua: <span class="text-gray-800 font-medium">{{ $item->chief_name ?? '-' }}</span></p>
                                <p class="text-xs text-gray-500">Santo: <span class="text-gray-800 font-medium">{{ $item->patron_saint ?? '-' }}</span></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.lingkungan.edit', $item->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded border border-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.lingkungan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus lingkungan ini? Data anggota terkait mungkin akan kehilangan relasi.');">
                                @csrf @method('DELETE')
                                <button class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded border border-red-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-5 text-center text-gray-400">Belum ada data lingkungan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $lingkungans->links() }}</div>
</div>
@endsection