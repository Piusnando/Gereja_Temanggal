@extends('layouts.admin')

@section('content')
<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

{{-- HEADER --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800">Struktur Organisasi</h1>
        <p class="text-sm text-gray-500">Kelola pengurus per Bidang dan Tim.</p>
    </div>
    
    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
        <!-- FILTER -->
        <form action="{{ route('admin.organization.index') }}" method="GET" class="flex items-center w-full sm:w-auto">
            <div class="relative w-full">
                <select name="bidang" onchange="this.form.submit()" class="appearance-none w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2 text-sm focus:outline-none focus:border-pink-500 bg-white">
                    <option value="">-- Semua Bidang --</option>
                    @foreach($bidangList as $b)
                        <option value="{{ $b }}" {{ $currentBidang == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </form>

        <a href="{{ route('admin.organization.create') }}" class="w-full sm:w-auto flex items-center justify-center bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg shadow text-sm transition">
            <span>+ Tambah</span>
        </a>
    </div>
</div>

{{-- NOTIFIKASI --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex justify-between">
    <span>{{ session('success') }}</span><button @click="show = false">&times;</button>
</div>
@endif
<div id="reorder-alert" class="hidden fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50">Urutan diperbarui!</div>

{{-- LOGIKA TAMPILAN BERTINGKAT --}}
@php
    $showList = $currentBidang ? [$currentBidang] : $bidangList;
@endphp

<div class="space-y-12 pb-10">
    @foreach($showList as $namaBidang)
        @php
            // 1. Ambil anggota bidang ini
            $allMembersInBidang = $members->where('bidang', $namaBidang);
            
            // 2. Kelompokkan berdasarkan Sub-Bidang (Tim)
            // Menggunakan sortKeys() agar nama tim urut abjad
            $groupedBySub = $allMembersInBidang->groupBy('sub_bidang')->sortKeys();
        @endphp

        {{-- Hanya render jika ada datanya --}}
        @if($allMembersInBidang->count() > 0)
        
        <div class="border-l-4 border-pink-500 pl-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $namaBidang }}</h2>
            
            <div class="space-y-6">
                {{-- Loop Sub Bidang (Tim) --}}
                @foreach($groupedBySub as $subName => $teamMembers)
                
                <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                    <!-- Header Sub Tim -->
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-md font-bold text-pink-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            {{ $subName }}
                        </h3>
                        <span class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-gray-500 font-mono">
                            {{ $teamMembers->count() }} Org
                        </span>
                    </div>

                    <!-- Tabel Anggota -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <tbody class="sortable-list divide-y divide-gray-100" data-sub="{{ $subName }}">
                                @foreach($teamMembers as $member)
                                <tr data-id="{{ $member->id }}" class="hover:bg-pink-50 transition bg-white group cursor-move">
                                    <!-- Drag Handle -->
                                    <td class="w-10 px-4 py-3 text-center align-middle">
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-pink-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </td>

                                    <!-- Foto & Nama -->
                                    <td class="px-4 py-3 align-middle">
                                        <div class="flex items-center gap-3">
                                            <div class="shrink-0 h-10 w-10 rounded-full overflow-hidden border border-gray-200">
                                                @if($member->image)
                                                    <img src="{{ asset('storage/' . $member->image) }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold">{{ substr($member->name, 0, 1) }}</div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 text-sm">{{ $member->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $member->position }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Lingkungan -->
                                    <td class="px-4 py-3 text-xs text-gray-500 hidden md:table-cell align-middle">
                                        {{ $member->lingkungan->name ?? '-' }}
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-3 text-center align-middle w-24">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('admin.organization.edit', $member->id) }}" class="text-blue-500 hover:text-blue-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                            <form action="{{ route('admin.organization.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                                @csrf @method('DELETE')
                                                <button class="text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.sortable-list').forEach(table => {
            new Sortable(table, {
                animation: 150,
                ghostClass: 'bg-pink-50',
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
            .then(res => res.json())
            .then(data => {
                let alert = document.getElementById('reorder-alert');
                alert.classList.remove('hidden');
                setTimeout(() => alert.classList.add('hidden'), 2000);
            });
        }
    });
</script>
@endsection