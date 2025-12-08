@extends('layouts.admin')

@section('content')
<!-- Tambahkan Library SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Struktur Organisasi</h1>
        <p class="text-sm text-gray-500">Geser baris tabel untuk mengubah urutan anggota.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <!-- FORM FILTER -->
        <form action="{{ route('admin.organization.index') }}" method="GET" class="flex items-center">
            <div class="relative">
                <select name="category" onchange="this.form.submit()" class="appearance-none border border-gray-300 rounded-lg pl-4 pr-10 py-2 text-sm focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                    <option value="">-- Tampilkan Semua Bagian --</option>
                    @foreach($allowed as $cat)
                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </form>

        <a href="{{ route('admin.organization.create') }}" class="flex items-center justify-center bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition duration-150 ease-in-out">
            + Tambah Anggota
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm">
    {{ session('success') }}
</div>
@endif

<!-- Notifikasi AJAX (Hidden by default) -->
<div id="reorder-alert" class="hidden fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300">
    Urutan berhasil diperbarui!
</div>

@php
    $categoriesToShow = $category ? [$category] : $allowed;
@endphp

<div class="space-y-8 pb-10">
    @foreach($categoriesToShow as $currentCat)
        @php
            $catMembers = $members->filter(function($item) use ($currentCat) {
                return $item->category === $currentCat;
            });
        @endphp

        @if($catMembers->count() > 0 || $category)
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2 h-6 bg-pink-500 rounded-full"></span>
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
                            <th class="w-10 px-6 py-3 text-center"></th>
                            <th class="px-6 py-3 text-left">Foto</th>
                            <th class="px-6 py-3 text-left">Nama & Jabatan</th>
                            <th class="px-6 py-3 text-left">Lingkungan</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <!-- Tambahkan ID unik per kategori dan class untuk JS -->
                    <tbody class="sortable-list text-gray-700 text-sm divide-y divide-gray-100" data-category="{{ $currentCat }}">
                        @forelse($catMembers as $member)
                        <!-- Tambahkan data-id agar JS tahu ID mana yang digeser -->
                        <tr data-id="{{ $member->id }}" class="hover:bg-pink-50 transition duration-150 bg-white group cursor-move">
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-pink-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <div class="shrink-0 h-10 w-10">
                                    @if($member->image)
                                        <img src="{{ asset('storage/' . $member->image) }}" class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm pointer-events-none">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs shadow-sm">
                                            {{ substr($member->name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">{{ $member->name }}</span>
                                    <span class="text-xs text-gray-500 mt-0.5">{{ $member->position }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                {{ $member->lingkungan->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-no-wrap text-sm font-medium">
                                <div class="flex justify-center gap-4">
                                    <a href="{{ route('admin.organization.edit', $member->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('admin.organization.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Hapus?');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-0 p-0 cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
                                Belum ada anggota.
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

<!-- SCRIPT SORTABLE JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cari semua elemen dengan class 'sortable-list'
        const tables = document.querySelectorAll('.sortable-list');

        tables.forEach(table => {
            new Sortable(table, {
                animation: 150, // Durasi animasi dalam ms
                ghostClass: 'bg-pink-100', // Class saat elemen sedang ditarik
                handle: 'tr', // Seluruh baris bisa ditarik
                
                // Event saat selesai drop
                onEnd: function (evt) {
                    // Ambil semua ID dalam tabel ini setelah diurutkan
                    let itemIds = Array.from(table.querySelectorAll('tr')).map(row => row.getAttribute('data-id'));
                    
                    // Kirim ke server via AJAX
                    updateOrder(itemIds);
                }
            });
        });

        function updateOrder(ids) {
            fetch("{{ route('admin.organization.reorder') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Tampilkan notifikasi kecil
                    const alert = document.getElementById('reorder-alert');
                    alert.classList.remove('hidden');
                    setTimeout(() => {
                        alert.classList.add('hidden');
                    }, 2000);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>

@endsection