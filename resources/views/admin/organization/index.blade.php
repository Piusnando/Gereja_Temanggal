@extends('layouts.admin')

@section('content')
<!-- Tambahkan Library SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

{{-- HEADER HALAMAN --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Struktur Organisasi</h1>
        <p class="text-sm text-gray-500">Geser baris untuk mengubah urutan anggota.</p>
    </div>
    
    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
        <!-- FORM FILTER -->
        <form action="{{ route('admin.organization.index') }}" method="GET" class="flex items-center w-full sm:w-auto">
            <div class="relative w-full">
                <select name="category" onchange="this.form.submit()" class="appearance-none w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2 text-sm focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                    <option value="">-- Tampilkan Semua --</option>
                    @foreach($allowed as $cat)
                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </form>

        <a href="{{ route('admin.organization.create') }}" class="w-full sm:w-auto flex items-center justify-center bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg shadow text-sm transition duration-150 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span>Tambah</span>
        </a>
    </div>
</div>

{{-- FLASH & NOTIFIKASI --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex justify-between">
    <span>{{ session('success') }}</span>
    <button @click="show = false">&times;</button>
</div>
@endif

<div id="reorder-alert" class="hidden fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300">
    Urutan berhasil diperbarui!
</div>

{{-- LOOP KATEGORI --}}
@php
    $categoriesToShow = $category ? [$category] : $allowed;
@endphp

<div class="space-y-8 pb-10">
    @foreach($categoriesToShow as $currentCat)
        @php
            $catMembers = $members->filter(fn($item) => $item->category === $currentCat);
        @endphp

        @if($catMembers->count() > 0 || $category)
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-pink-500 rounded-full"></span>
                    {{ $currentCat }}
                </h2>
                <span class="text-xs font-semibold text-gray-500 bg-gray-200 px-2 py-1 rounded-md">
                    {{ $catMembers->count() }} Anggota
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead class="bg-white text-gray-600 uppercase text-xs font-semibold border-b">
                        <tr>
                            <th class="w-10 px-4 py-3 text-center"></th> <!-- Drag Handle -->
                            <th class="px-4 py-3 text-left">Anggota</th>
                            <th class="px-4 py-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="sortable-list text-gray-700 text-sm divide-y divide-gray-100" data-category="{{ $currentCat }}">
                        @forelse($catMembers as $member)
                        <tr data-id="{{ $member->id }}" class="hover:bg-pink-50 transition duration-150 bg-white group cursor-move">
                            
                            <!-- 1. DRAG HANDLE -->
                            <td class="px-4 py-4 text-center align-middle">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-pink-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </td>

                            <!-- 2. KOLOM ANGGOTA (RESPONSIVE) -->
                            <td class="px-4 py-4 align-top">
                                <div class="flex items-center gap-4">
                                    <!-- Foto -->
                                    <div class="shrink-0 h-12 w-12 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                        @if($member->image)
                                            <img src="{{ asset('storage/' . $member->image) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-lg">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Detail Teks -->
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 text-base leading-tight truncate group-hover:text-pink-500">{{ $member->name }}</p>
                                        <p class="text-[10px] md:text-xs font-bold uppercase tracking-wider text-white bg-pink-500 inline-block px-2 py-0.5 rounded mt-1">{{ $member->position }}</p>
                                        @if($member->lingkungan)
                                        <p class="text-xs text-gray-500 flex items-center mt-1 truncate">
                                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $member->lingkungan->name }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- 3. KOLOM AKSI (IKON) -->
                            <td class="px-4 py-4 text-center align-middle">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('admin.organization.edit', $member->id) }}" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-100" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.organization.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Hapus anggota ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-lg bg-red-50 hover:bg-red-100 border border-red-100" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
                                Belum ada anggota untuk kategori ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endforeach
</div>

<!-- SCRIPT SORTABLE JS (Tidak Berubah) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tables = document.querySelectorAll('.sortable-list');
        tables.forEach(table => {
            new Sortable(table, {
                animation: 150,
                ghostClass: 'bg-pink-100',
                handle: 'tr',
                onEnd: function (evt) {
                    let itemIds = Array.from(table.querySelectorAll('tr')).map(row => row.getAttribute('data-id'));
                    updateOrder(itemIds);
                }
            });
        });

        function updateOrder(ids) {
            fetch("{{ route('admin.organization.reorder') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    const alert = document.getElementById('reorder-alert');
                    alert.classList.remove('hidden');
                    setTimeout(() => { alert.classList.add('hidden'); }, 2000);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>

@endsection