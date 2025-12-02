<tr class="hover:bg-gray-50 transition">
    <td class="px-5 py-4 border-b border-gray-200 text-sm font-bold text-gray-800">
        {{ $person->name }}
    </td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">
        @if($person->is_external)
            {{ $person->external_description }}
        @else
            {{ $person->lingkungan->name ?? '-' }}
        @endif
    </td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">
        @if($person->is_external)
            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">Luar Paroki</span>
        @else
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">Internal</span>
        @endif
    </td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm text-center">
        <!-- Tombol Hapus -->
        <form action="{{ route('admin.liturgy.personnels.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data {{ $person->name }}?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition flex items-center justify-center mx-auto" title="Hapus">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </form>
    </td>
</tr>