@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Data Barang Inventaris</h1>
        <p class="text-sm text-gray-500">Daftar semua aset gereja.</p>
    </div>
    <a href="{{ route('admin.inventaris.items.create') }}" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition flex justify-center items-center">
        + Input Barang Baru
    </a>
</div>

{{-- Filter Form --}}
<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6">
    <form action="{{ route('admin.inventaris.items.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        {{-- ... Kode filter Anda sebelumnya ... --}}
    </form>
</div>

{{-- Flash Messages --}}
@if(session('success')) <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div> @endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <!-- TAMPILAN DESKTOP -->
    <div class="hidden md:block">
        {{-- ... Kode tabel desktop lama Anda ... --}}
    </div>

    <!-- TAMPILAN MOBILE -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($items as $item)
        <div class="p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="font-bold text-gray-800 text-base">{{ $item->name }}</p>
                    <span class="font-mono text-green-700 bg-green-50 px-2 py-0.5 text-xs rounded border border-green-200">{{ $item->item_code }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.inventaris.items.edit', $item->id) }}" class="p-2 bg-blue-50 rounded"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                    <form action="{{ route('admin.inventaris.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');"> @csrf @method('DELETE') <button type="submit" class="p-2 bg-red-50 rounded"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button> </form>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600">
                <!-- Kondisi -->
                @php $condClass = match($item->condition) { 'Baik'=>'bg-blue-100 text-blue-800','Rusak Sedang'=>'bg-yellow-100 text-yellow-800','Rusak Berat'=>'bg-red-100 text-red-800', default=>'bg-gray-100'}; @endphp
                <span class="px-2 py-0.5 rounded-full font-bold {{ $condClass }}">{{ $item->condition }}</span>
                <!-- Lokasi -->
                <span class="flex items-center"><svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $item->location->name }}</span>
                <!-- Kategori -->
                <span class="flex items-center"><svg class="w-3 h-3 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> {{ $item->category->name }}</span>
            </div>
        </div>
        @empty
        <div class="p-5 text-center text-gray-400">Belum ada data barang.</div>
        @endforelse
    </div>
    
    <div class="p-4 bg-gray-50 border-t border-gray-100">
        {{ $items->links() }}
    </div>
</div>
@endsection