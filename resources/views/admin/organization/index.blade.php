@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Struktur Organisasi</h1>
    
    <div class="flex gap-2">
        <!-- FORM FILTER KATEGORI -->
        <form action="{{ route('admin.organization.index') }}" method="GET" class="flex items-center">
            <select name="category" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500">
                <option value="">-- Semua Kategori --</option>
                @foreach($allowed as $cat)
                    <!-- Perhatikan: $category di sini sekarang sudah dikirim dari controller -->
                    <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.organization.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
            + Tambah Anggota
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
            <tr>
                <th class="px-5 py-3 text-left">Foto</th>
                <th class="px-5 py-3 text-left">Nama & Jabatan</th>
                <th class="px-5 py-3 text-left">Kategori</th>
                <th class="px-5 py-3 text-left">Lingkungan</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 text-sm">
            @forelse($members as $member)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-5 py-4">
                    @if($member->image)
                        <img src="{{ asset('storage/' . $member->image) }}" class="w-10 h-10 rounded-full object-cover border">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                            {{ substr($member->name, 0, 2) }}
                        </div>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <p class="font-bold text-gray-900">{{ $member->name }}</p>
                    <p class="text-xs text-gray-500">{{ $member->position }}</p>
                </td>
                <td class="px-5 py-4">
                    <span class="bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded-full font-bold">
                        {{ $member->category }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    {{ $member->lingkungan->name ?? '-' }}
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('admin.organization.edit', $member->id) }}" class="text-blue-600 hover:text-blue-900 font-bold">Edit</a>
                        <form action="{{ route('admin.organization.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Yakin hapus anggota ini?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                    Belum ada data anggota organisasi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $members->links() }}</div>
</div>
@endsection