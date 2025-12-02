<tr class="hover:bg-gray-50 transition">
    <!-- KOLOM NAMA DENGAN COUNTER -->
    <td class="px-5 py-4 border-b border-gray-200 text-sm">
        <div class="flex items-center">
            <span class="font-bold text-gray-800">{{ $person->name }}</span>
            
            {{-- Badge Jumlah Tugas --}}
            @if($person->assignments_count > 0)
                <span class="ml-2 bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-200" title="Total Penugasan">
                    {{ $person->assignments_count }}x
                </span>
            @else
                <span class="ml-2 text-gray-400 text-[10px] italic">
                    (Baru)
                </span>
            @endif
        </div>
    </td>

    <!-- KOLOM ASAL (Tetap) -->
    <td class="px-5 py-4 border-b border-gray-200 text-sm">
        @if($person->is_external)
            {{ $person->external_description }}
        @else
            {{ $person->lingkungan->name ?? '-' }}
        @endif
    </td>

    <!-- KOLOM STATUS (Tetap) -->
    <td class="px-5 py-4 border-b border-gray-200 text-sm">
        @if($person->is_external)
            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">Luar Paroki</span>
        @else
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">Internal</span>
        @endif
    </td>

    <!-- KOLOM AKSI (Tetap) -->
    <td class="px-5 py-4 border-b border-gray-200 text-sm text-center">
        <form action="{{ route('admin.liturgy.personnels.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data {{ $person->name }}?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition flex items-center justify-center mx-auto" title="Hapus">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </form>
    </td>
</tr>