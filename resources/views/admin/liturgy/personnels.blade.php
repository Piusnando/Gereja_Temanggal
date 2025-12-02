@extends('layouts.admin')

@section('content')

{{-- HEADER HALAMAN --}}
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        @if($type)
            Data {{ $type }}
        @else
            Database Semua Petugas
        @endif
    </h1>
    
    {{-- Tombol Tambah hanya muncul jika sedang membuka kategori spesifik --}}
    @if($type)
    <a href="{{ route('admin.liturgy.personnels.create', ['type' => $type]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Tambah {{ $type }}
    </a>
    @endif
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
    <p>{{ session('success') }}</p>
</div>
@endif
@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
    <p>{{ session('error') }}</p>
</div>
@endif


{{-- ======================================================================= --}}
{{-- MODE 1: TAMPILAN TERPISAH (ADMIN ALL) --}}
{{-- ======================================================================= --}}
@if(!isset($personnels)) 
    
    <div class="space-y-12"> <!-- Jarak antar tabel -->
        @foreach($groupedData as $kategori => $listPetugas)
            <div class="bg-white rounded-lg shadow overflow-hidden border-t-4 
                {{ $kategori == 'Misdinar' ? 'border-red-500' : 
                  ($kategori == 'Lektor' ? 'border-green-500' : 
                  ($kategori == 'Mazmur' ? 'border-yellow-500' : 'border-purple-500')) }}">
                
                <!-- Header Per Tabel -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-700 flex items-center">
                        {{ $kategori }}
                        <span class="ml-2 bg-gray-200 text-gray-600 text-xs py-1 px-2 rounded-full">
                            {{ $listPetugas->count() }}
                        </span>
                    </h2>
                    
                    <!-- Tombol Tambah Cepat per Kategori -->
                    <a href="{{ route('admin.liturgy.personnels.create', ['type' => $kategori]) }}" class="text-sm text-blue-600 hover:text-blue-800 font-bold">
                        + Tambah {{ $kategori }}
                    </a>
                </div>

                <!-- Tabel -->
                <table class="min-w-full leading-normal">
                    <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left">Nama</th>
                            <th class="px-5 py-3 text-left">Asal / Lingkungan</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listPetugas as $person)
                            @include('admin.liturgy.partials.row_personnel', ['person' => $person])
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-4 text-center text-gray-400 text-sm italic">
                                    Belum ada data {{ $kategori }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

{{-- ======================================================================= --}}
{{-- MODE 2: TAMPILAN SATU JENIS (PAGINATION) --}}
{{-- ======================================================================= --}}
@else

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Petugas</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Asal / Lingkungan</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personnels as $person)
                    @include('admin.liturgy.partials.row_personnel', ['person' => $person])
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                        Belum ada data petugas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-5 py-5 bg-white border-t">
            {{ $personnels->appends(['type' => $type])->links() }}
        </div>
    </div>

@endif

@endsection