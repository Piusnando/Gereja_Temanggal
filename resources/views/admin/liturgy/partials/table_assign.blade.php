<div class="bg-white p-6 rounded shadow border-t-4 border-blue-500">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Petugas Terjadwal</h3>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
            {{ $schedule->assignments->count() }} Item
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border border-gray-200">
            <!-- Header Tabel -->
            <thead class="bg-gray-800 text-white uppercase text-xs font-bold">
                <tr>
                    <th class="p-3 border-b w-1/4">Peran</th>
                    <th class="p-3 border-b">Nama / Lingkungan</th>
                    <th class="p-3 border-b text-center w-20">Aksi</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100">
                @php
                    // Mengelompokkan data berdasarkan Role
                    $groupedAssignments = $schedule->assignments->groupBy('role');
                    
                    // Urutan Tampilan yang diinginkan
                    $urutanRole = ['Misdinar', 'Lektor', 'Mazmur', 'Organis', 'Paduan Suara', 'Parkir'];
                @endphp

                @foreach($urutanRole as $roleName)
                    {{-- Hanya tampilkan jika ada datanya di kategori ini --}}
                    @if(isset($groupedAssignments[$roleName]))
                        
                        <!-- Header Pemisah Kategori -->
                        <tr class="bg-gray-100 border-t-2 border-gray-200">
                            <td colspan="3" class="p-2 pl-3 text-xs font-extrabold text-gray-500 uppercase tracking-widest">
                                {{ $roleName }}
                            </td>
                        </tr>

                        <!-- Loop Item per Kategori -->
                        @foreach($groupedAssignments[$roleName] as $assign)
                        <tr class="hover:bg-blue-50 transition bg-white">
                            
                            <!-- KOLOM PERAN -->
                            <td class="p-3 font-bold text-blue-900 align-middle border-r border-gray-100">
                                {{ $assign->role }}
                            </td>

                            <!-- KOLOM NAMA -->
                            <td class="p-3 align-middle">
                                @if(in_array($assign->role, ['Paduan Suara', 'Parkir']))
                                    <!-- TUGAS KELOMPOK -->
                                    <div class="flex items-center">
                                        @if($assign->lingkungan)
                                            <span class="bg-green-100 text-green-700 p-1 rounded mr-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </span>
                                            <div>
                                                <span class="font-bold text-gray-800">{{ $assign->lingkungan->name }}</span>
                                                <div class="text-[10px] text-gray-500 uppercase tracking-wide">Tugas Wilayah</div>
                                            </div>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 p-1 rounded mr-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </span>
                                            <div>
                                                <span class="font-bold text-gray-800">{{ $assign->description }}</span>
                                                <div class="text-[10px] text-yellow-600 uppercase tracking-wide font-bold">Dari Luar Paroki</div>
                                            </div>
                                        @endif
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
                        @endforeach

                    @endif
                @endforeach

                @if($schedule->assignments->isEmpty())
                <tr>
                    <td colspan="3" class="p-8 text-center text-gray-400 italic bg-gray-50">
                        Belum ada petugas yang ditambahkan pada jadwal ini.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>