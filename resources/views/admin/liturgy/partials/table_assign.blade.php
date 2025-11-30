<div class="bg-white p-6 rounded shadow border-t-4 border-blue-500">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Petugas Terjadwal</h3>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
            {{ $schedule->assignments->count() }} Item
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border border-gray-200">
            <thead class="bg-gray-100 uppercase text-xs font-bold text-gray-600">
                <tr>
                    <th class="p-3 border-b">Peran</th>
                    <th class="p-3 border-b">Nama / Lingkungan</th>
                    <th class="p-3 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedule->assignments as $assign)
                <tr class="hover:bg-gray-50 transition">
                    <!-- KOLOM PERAN -->
                    <td class="p-3 font-bold text-blue-900 align-middle">
                        {{ $assign->role }}
                    </td>

                    <!-- KOLOM NAMA -->
                    <td class="p-3 align-middle">
                        @if(in_array($assign->role, ['Paduan Suara', 'Parkir']))
                            <!-- TUGAS KELOMPOK (LINGKUNGAN) -->
                            <div class="flex items-center">
                                <span class="bg-green-100 text-green-700 p-1 rounded mr-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                                <div>
                                    <span class="font-bold text-gray-800">{{ $assign->lingkungan->name ?? 'Lingkungan Terhapus' }}</span>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-wide">Tugas Wilayah</div>
                                </div>
                            </div>
                        @else
                            <!-- TUGAS PERORANGAN -->
                            <div class="flex items-center">
                                <span class="bg-gray-100 text-gray-600 p-1 rounded mr-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </span>
                                <div>
                                    <span class="font-bold text-gray-800">{{ $assign->personnel->name ?? 'Nama Terhapus' }}</span>
                                    <div class="text-[10px] text-gray-500">
                                        {{ $assign->personnel->is_external 
                                            ? 'Luar: ' . $assign->personnel->external_description 
                                            : ($assign->personnel->lingkungan->name ?? '-') 
                                        }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>

                    <!-- KOLOM AKSI -->
                    <td class="p-3 text-center align-middle">
                        <form action="{{ route('admin.liturgy.assign.destroy', $assign->id) }}" method="POST" onsubmit="return confirm('Hapus petugas ini dari jadwal?');">
                            @csrf 
                            @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-8 text-center text-gray-400 italic bg-gray-50">
                        Belum ada petugas yang ditambahkan pada jadwal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>