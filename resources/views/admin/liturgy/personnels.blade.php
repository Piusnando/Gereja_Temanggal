@extends('layouts.admin')

@section('content')

{{-- HEADER HALAMAN --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">
            @if($type)
                Data {{ $type }}
            @else
                Database Petugas Liturgi
            @endif
        </h1>
        <p class="text-sm text-gray-500">Kelola daftar nama petugas liturgi.</p>
    </div>
    
    @if($type)
    <a href="{{ route('admin.liturgy.personnels.create', ['type' => $type]) }}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex justify-center items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Tambah {{ $type }}
    </a>
    @endif
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
<div x-data="{show: true}" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex justify-between items-center" role="alert">
    <span>{{ session('success') }}</span>
    <button @click="show = false" class="font-bold">&times;</button>
</div>
@endif
@if(session('error'))
<div x-data="{show: true}" x-show="show" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 flex justify-between items-center" role="alert">
    <span>{{ session('error') }}</span>
    <button @click="show = false" class="font-bold">&times;</button>
</div>
@endif


{{-- MODE 1: TAMPILAN ADMIN (GROUPED) --}}
@if(!isset($personnels)) 
    
    <div class="space-y-12">
        @foreach($groupedData as $kategori => $listPetugas)
            <div class="bg-white rounded-lg shadow overflow-hidden border-t-4 
                {{ $kategori == 'Misdinar' ? 'border-red-500' : 
                  ($kategori == 'Lektor' ? 'border-green-500' : 
                  ($kategori == 'Mazmur' ? 'border-yellow-500' : 'border-purple-500')) }}">
                
                <div class="px-4 md:px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-700 flex items-center">
                        {{ $kategori }}
                        <span class="ml-2 bg-gray-200 text-gray-600 text-xs py-1 px-2 rounded-full">
                            {{ $listPetugas->count() }}
                        </span>
                    </h2>
                    <a href="{{ route('admin.liturgy.personnels.create', ['type' => $kategori]) }}" class="text-sm text-blue-600 hover:text-blue-800 font-bold">
                        + Tambah
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">Detail Petugas</th>
                                <th class="px-4 py-3 text-left hidden md:table-cell">Asal</th>
                                <th class="px-4 py-3 text-left hidden md:table-cell">Status</th>
                                <th class="px-4 py-3 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listPetugas as $person)
                                @include('admin.liturgy.partials.row_personnel', ['person' => $person])
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-gray-400 text-sm italic">
                                        Belum ada data {{ strtolower($kategori) }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

{{-- MODE 2: TAMPILAN USER BIASA (PAGINATED) --}}
@else

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Detail Petugas</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Asal</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Status</th>
                        <th class="px-4 py-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personnels as $person)
                        @include('admin.liturgy.partials.row_personnel', ['person' => $person])
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                            Belum ada data petugas untuk kategori ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-5 py-4 bg-white border-t">
            {{ $personnels->appends(['type' => $type])->links() }}
        </div>
    </div>

@endif

@endsection