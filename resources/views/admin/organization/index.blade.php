@extends('layouts.admin')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Organisasi</h1>
            <p class="text-sm text-gray-500">Struktur: <span class="font-bold text-blue-600">{{ $category }}</span></p>
        </div>
        
        <a href="{{ route('admin.organization.create', ['category' => $category]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Anggota
        </a>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center" role="alert">
        <div><strong class="font-bold">Berhasil!</strong> <span class="block sm:inline">{{ session('success') }}</span></div>
        <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">×</button>
    </div>
    @endif

    {{-- PILIHAN KATEGORI --}}
    <div class="mb-8">
        <div class="flex flex-wrap gap-2">
            @foreach($categories as $cat)
                <a href="{{ route('admin.organization.index', ['category' => $cat]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold border transition duration-200 
                   {{ $category == $cat ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:text-blue-600' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3">Nama Lengkap</th>
                    <th class="px-5 py-3">Jabatan</th>
                    <th class="px-5 py-3">Asal Lingkungan</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($members as $member)
                <tr class="hover:bg-blue-50 transition bg-white">
                    
                    <!-- KOLOM NAMA & FOTO -->
                    <td class="px-5 py-4 font-bold text-gray-800">
                        <div class="flex items-center">
                            
                            <!-- LOGIKA TAMPIL FOTO / INISIAL -->
                            <div class="w-10 h-10 rounded-full overflow-hidden mr-3 border border-gray-200 shrink-0">
                                @if($member->image)
                                    <img src="{{ asset('storage/' . $member->image) }}" class="w-full h-full object-cover" alt="{{ $member->name }}">
                                @else
                                    <div class="w-full h-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            {{ $member->name }}
                        </div>
                    </td>
                    
                    <!-- Jabatan -->
                    <td class="px-5 py-4">
                        <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded border border-blue-100 font-semibold">
                            {{ $member->position }}
                        </span>
                    </td>
                    
                    <!-- Lingkungan -->
                    <td class="px-5 py-4 text-gray-500">
                        @if($member->lingkungan)
                            {{ $member->lingkungan->name }}
                        @else
                            <span class="text-gray-400 italic text-xs">Tidak ada data lingkungan</span>
                        @endif
                    </td>
                    
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            <!-- TOMBOL EDIT -->
                            <a href="{{ route('admin.organization.edit', $member->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded transition" title="Edit Anggota">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>

                            <!-- TOMBOL HAPUS -->
                            <form action="{{ route('admin.organization.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $member->name }} dari daftar?');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition" title="Hapus Anggota">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center bg-gray-50 text-gray-400">
                        <p class="text-sm">Belum ada data anggota untuk kategori <span class="font-bold">{{ $category }}</span>.</p>
                        <p class="text-xs mt-1">Silakan tambahkan anggota baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- PAGINATION --}}
        <div class="px-5 py-4 bg-white border-t border-gray-200">
            {{ $members->appends(['category' => $category])->links() }}
        </div>
    </div>

@endsection