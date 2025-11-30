@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data {{ $type ?? 'Semua Petugas' }}</h1>
    
    <!-- Tombol Tambah Spesifik -->
    <a href="{{ route('admin.liturgy.personnels.create', ['type' => $type]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Tambah {{ $type ?? 'Petugas' }}
    </a>
</div>

<!-- Flash Message -->
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
    <p>{{ session('success') }}</p>
</div>
@endif

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
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-gray-800">
                    {{ $person->name }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    @if($person->is_external)
                        {{ $person->external_description }}
                    @else
                        {{ $person->lingkungan->name ?? '-' }}
                    @endif
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    @if($person->is_external)
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">Luar Paroki</span>
                    @else
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">Internal</span>
                    @endif
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <button class="text-red-600 hover:text-red-900 text-sm font-bold" disabled title="Fitur hapus belum diaktifkan demi keamanan data">
                        Hapus
                    </button>
                    <!-- Jika ingin mengaktifkan hapus, buat route destroy dan form delete seperti di pengumuman -->
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                    Belum ada data petugas. Silakan tambahkan petugas baru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-5 py-5 bg-white border-t">
        {{ $personnels->links() }}
    </div>
</div>
@endsection