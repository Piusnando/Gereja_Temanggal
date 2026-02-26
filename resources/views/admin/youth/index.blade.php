@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Anggota {{ $category }}</h1>
    <a href="{{ route('admin.youth.members.create', ['category' => $category]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Tambah {{ $category }}
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
            <tr>
                <th class="px-5 py-3 text-left">Nama</th>
                <th class="px-5 py-3 text-left">Lingkungan</th>
                <th class="px-5 py-3 text-left">No. HP</th>
                <th class="px-5 py-3 text-center">Status</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 text-sm">
            @forelse($members as $m)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-5 py-4 font-bold">{{ $m->name }}</td>
                <td class="px-5 py-4">{{ $m->lingkungan->name ?? '-' }}</td>
                <td class="px-5 py-4">{{ $m->phone ?? '-' }}</td>
                <td class="px-5 py-4 text-center">
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $m->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $m->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-center flex justify-center gap-2">
                    <a href="{{ route('admin.youth.members.edit', $m->id) }}" class="text-blue-600 hover:text-blue-800 font-bold border border-blue-100 bg-blue-50 p-2 rounded">Edit</a>
                    <form action="{{ route('admin.youth.members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus anggota ini?');">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-800 font-bold border border-red-100 bg-red-50 p-2 rounded">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada data {{ $category }}.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $members->appends(['category' => $category])->links() }}</div>
</div>
@endsection