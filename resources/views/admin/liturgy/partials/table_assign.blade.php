<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Petugas Terjadwal</h3>
        <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
            {{ $schedule->assignments->count() }} Orang
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-500 uppercase text-xs font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-3 border-b">Peran</th>
                    <th class="px-6 py-3 border-b">Nama / Lingkungan</th>
                    <th class="px-6 py-3 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                {{-- 
                    LOGIKA SORTING: 
                    Kita urutkan data berdasarkan Peran agar rapi (Misdinar kumpul Misdinar, dst) 
                --}}
                @php
                    $roleOrder = [
                        'Misdinar' => 1, 
                        'Lektor' => 2, 
                        'Mazmur' => 3, 
                        'Organis' => 4, 
                        'Paduan Suara' => 5, 
                        'Parkir' => 6
                    ];
                    
                    $sortedAssignments = $schedule->assignments->sortBy(function($item) use ($roleOrder) {
                        return $roleOrder[$item->role] ?? 99;
                    });
                @endphp

                @forelse($sortedAssignments as $assign)
                <tr class="hover:bg-blue-50/50 transition duration-150 group">
                    
                    <!-- KOLOM PERAN (Dibuat Badge/Label) -->
                    <td class="px-6 py-4 align-middle whitespace-nowrap w-[20%]">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wide
                            {{ in_array($assign->role, ['Paduan Suara', 'Parkir']) ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $assign->role }}
                        </span>
                    </td>

                    <!-- KOLOM NAMA (Alignment Diperbaiki) -->
                    <td class="px-6 py-4 align-middle">
                        <div class="flex items-center">
                            
                            {{-- LOGIKA IKON & FORMAT --}}
                            @if($assign->lingkungan)
                                <!-- Icon Lingkungan (Hijau) -->
                                <div class="h-10 w-10 shrink-0 flex items-center justify-center rounded-full bg-green-100 text-green-600 mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $assign->lingkungan->name }}</div>
                                    <div class="text-xs text-gray-500 uppercase font-semibold tracking-wide">
                                        {{ $assign->lingkungan->territory->name ?? 'Wilayah' }}
                                    </div>
                                </div>

                            @elseif($assign->personnel)
                                @if($assign->personnel->is_external)
                                    <!-- Icon Eksternal (Kuning) -->
                                    <div class="h-10 w-10 shrink-0 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $assign->personnel->name }}</div>
                                        <div class="text-xs text-yellow-600 font-semibold">
                                            Dari Luar: {{ $assign->personnel->external_description }}
                                        </div>
                                    </div>
                                @else
                                    <!-- Icon Internal (Abu/Biru) -->
                                    <div class="h-10 w-10 shrink-0 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 mr-4 group-hover:bg-blue-100 group-hover:text-blue-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $assign->personnel->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $assign->personnel->lingkungan->name ?? '-' }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <span class="text-red-400 italic text-xs">Data Corrupt</span>
                            @endif
                        </div>
                    </td>

                    <!-- KOLOM AKSI -->
                    <td class="px-6 py-4 text-center align-middle w-24">
                        <form action="{{ route('admin.liturgy.assign.destroy', $assign->id) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?');">
                            @csrf 
                            @method('DELETE')
                            <button class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition shadow-sm" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                        <div class="bg-gray-100 p-3 rounded-full mb-3">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <p class="font-medium">Belum ada petugas.</p>
                        <p class="text-xs">Gunakan form di sebelah kiri untuk menambah.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>