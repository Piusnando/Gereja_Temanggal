<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Petugas Terjadwal</h3>
        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
            {{ $schedule->assignments->count() }} Item
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-bold">
                <tr>
                    <th class="px-6 py-3 border-b">Peran</th>
                    <th class="px-6 py-3 border-b">Nama / Lingkungan</th>
                    <th class="px-6 py-3 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedule->assignments as $assign)
                <tr class="hover:bg-gray-50 transition duration-150">
                    
                    <!-- KOLOM PERAN -->
                    <td class="px-6 py-4 font-bold text-blue-900 align-middle w-1/4">
                        {{ $assign->role }}
                    </td>

                    <!-- KOLOM NAMA (LOGIKA DIPERBAIKI) -->
                    <td class="px-6 py-4 align-middle">
                        
                        {{-- KASUS 1: TUGAS KELOMPOK DARI LINGKUNGAN INTERNAL --}}
                        @if($assign->lingkungan)
                            <div class="flex items-center">
                                <span class="bg-green-100 text-green-600 p-2 rounded-md mr-3 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                                <div>
                                    <span class="font-bold text-gray-800 block text-base">
                                        {{ $assign->lingkungan->name }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-semibold tracking-wide uppercase">
                                        {{ $assign->lingkungan->territory->name ?? 'Tugas Wilayah' }}
                                    </span>
                                </div>
                            </div>

                        {{-- KASUS 2: TUGAS PERORANGAN ATAU KELOMPOK EKSTERNAL (Yg tersimpan di tabel Personnel) --}}
                        @elseif($assign->personnel)
                            <div class="flex items-center">
                                @if($assign->personnel->is_external)
                                    <!-- Icon Globe Kuning untuk Eksternal -->
                                    <span class="bg-yellow-100 text-yellow-600 p-2 rounded-md mr-3 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                    <div>
                                        <span class="font-bold text-gray-800 block text-base">
                                            {{ $assign->personnel->name }} <!-- Nama Padus/Orang -->
                                        </span>
                                        <span class="text-xs text-yellow-700 font-semibold uppercase">
                                            Luar: {{ $assign->personnel->external_description }} <!-- Asal -->
                                        </span>
                                    </div>
                                @else
                                    <!-- Icon User Abu untuk Internal -->
                                    <span class="bg-gray-100 text-gray-500 p-2 rounded-md mr-3 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </span>
                                    <div>
                                        <span class="font-bold text-gray-800 block text-base">
                                            {{ $assign->personnel->name }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $assign->personnel->lingkungan->name ?? '-' }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                        {{-- KASUS 3: DATA CORRUPT/HILANG --}}
                        @else
                            <span class="text-red-400 italic text-xs">Data tidak ditemukan</span>
                        @endif
                    </td>

                    <!-- KOLOM AKSI -->
                    <td class="px-6 py-4 text-center align-middle w-24">
                        <form action="{{ route('admin.liturgy.assign.destroy', $assign->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus petugas ini?');">
                            @csrf 
                            @method('DELETE')
                            <button class="bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-700 hover:border-red-300 p-2 rounded-lg transition duration-200 shadow-sm" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <p class="font-medium">Belum ada petugas yang ditambahkan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>