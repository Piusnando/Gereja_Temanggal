@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Anggota {{ $category }}</h1>
        <p class="text-sm text-gray-500">Manajemen data umat {{ $category }}.</p>
    </div>
    
    <a href="{{ route('admin.youth.members.create', ['category' => $category]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Anggota
    </a>
</div>

{{-- Flash Message --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
    <span>{{ session('success') }}</span>
    <button @click="show = false" class="text-green-700 font-bold">&times;</button>
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left">Nama Lengkap</th>
                    <th class="px-5 py-3 text-left">Lingkungan</th>
                    <th class="px-5 py-3 text-left">No. HP</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                @forelse($members as $m)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 font-bold text-gray-800">{{ $m->name }}</td>
                    <td class="px-5 py-4">{{ $m->lingkungan->name ?? '-' }}</td>
                    <td class="px-5 py-4">{{ $m->phone ?? '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $m->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $m->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.youth.members.edit', $m->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded border border-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.youth.members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus data {{ $m->name }}? Data kehadiran juga akan terhapus.');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400 italic bg-gray-50">
                        Belum ada data anggota {{ $category }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-white border-t border-gray-200">
        {{ $members->appends(['category' => $category])->links() }}
    </div>
</div>
@endsection