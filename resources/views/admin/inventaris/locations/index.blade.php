@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Master Data Lokasi</h1>
        <p class="text-sm text-gray-500">Kelola lokasi penyimpanan barang inventaris.</p>
    </div>
    <a href="{{ route('admin.inventaris.locations.create') }}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition flex justify-center items-center">
        + Tambah Lokasi
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <!-- TAMPILAN DESKTOP (TABLE) -->
    <div class="hidden md:block">
        <table class="min-w-full leading-normal">
            {{-- ... thead dan tbody untuk desktop (biarkan kode lama Anda) ... --}}
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-5 py-3 text-left">Nama Lokasi</th>
                    <th class="px-5 py-3 text-left">Kode</th>
                    <th class="px-5 py-3 text-center">Jumlah Barang</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                @forelse($locations as $location)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 font-bold">{{ $location->name }}</td>
                    <td class="px-5 py-4"><span class="bg-gray-100 font-mono px-2 py-1 rounded border">{{ $location->code }}</span></td>
                    <td class="px-5 py-4 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-bold">{{ $location->items->count() }} Item</span></td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.inventaris.locations.edit', $location->id) }}" class="p-2"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                            <form action="{{ route('admin.inventaris.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Hapus?');"> @csrf @method('DELETE') <button type="submit" class="p-2"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button> </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-5 text-center text-gray-400">Belum ada data lokasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- TAMPILAN MOBILE (CARD) -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($locations as $location)
        <div class="p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-gray-800">{{ $location->name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="bg-gray-100 font-mono px-2 py-0.5 text-xs rounded border">{{ $location->code }}</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs font-bold">{{ $location->items->count() }} Item</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.inventaris.locations.edit', $location->id) }}" class="p-2 bg-blue-50 rounded"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                    <form action="{{ route('admin.inventaris.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Hapus?');"> @csrf @method('DELETE') <button type="submit" class="p-2 bg-red-50 rounded"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button> </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-5 text-center text-gray-400">Belum ada data lokasi.</div>
        @endforelse
    </div>
    
    <div class="p-4 bg-gray-50 border-t border-gray-100">
        {{ $locations->links() }}
    </div>
</div>
@endsection