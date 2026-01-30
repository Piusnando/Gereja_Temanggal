@extends('layouts.admin')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    
    <!-- Judul -->
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <svg class="w-8 h-8 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        Kritik & Saran Masuk
    </h1>

    <!-- Form Search Otomatis -->
    <form id="searchForm" action="{{ route('admin.feedback.index') }}" method="GET" class="w-full md:w-auto">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- 
                PERUBAHAN DISINI:
                1. Menambahkan oninput="autoSearch()"
                2. Menghapus tombol submit manual
            -->
            <input type="text" 
                   name="search" 
                   id="searchInput"
                   value="{{ request('search') }}" 
                   oninput="autoSearch()"
                   class="block w-full md:w-72 p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" 
                   placeholder="Ketik untuk mencari...">
            
            <!-- Tombol Reset (Muncul jika sedang mencari) -->
            @if(request('search'))
                <a href="{{ route('admin.feedback.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-red-500 hover:text-red-700 font-bold text-lg" title="Hapus Filter">
                    &times;
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Flash Message -->
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Tanggal</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Isi Pesan</th>
                <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($feedbacks as $item)
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-5 py-4 text-sm text-gray-600 align-top whitespace-nowrap">
                    {{ $item->created_at->format('d M Y') }}
                    <span class="block text-xs text-gray-400 mt-1">{{ $item->created_at->format('H:i') }} WIB</span>
                </td>
                <td class="px-5 py-4 text-sm text-gray-800 align-top leading-relaxed">
                    {{-- Highlight kata kunci yang dicari --}}
                    {!! str_ireplace(request('search'), '<span class="bg-yellow-200 font-bold">'.request('search').'</span>', e($item->message)) !!}
                </td>
                <td class="px-5 py-4 text-center align-top">
                    <form action="{{ route('admin.feedback.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pesan ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition" title="Hapus Pesan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-5 py-10 text-center text-gray-400 bg-gray-50">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        @if(request('search'))
                            <p>Tidak ditemukan pesan dengan kata kunci "<strong>{{ request('search') }}</strong>"</p>
                            <a href="{{ route('admin.feedback.index') }}" class="mt-2 text-blue-600 hover:underline text-sm">Reset Pencarian</a>
                        @else
                            <p>Belum ada kritik & saran yang masuk.</p>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination (tetap membawa query search) -->
    <div class="px-5 py-4 border-t border-gray-200 bg-white">
        {{ $feedbacks->appends(['search' => request('search')])->links() }}
    </div>
</div>

<!-- SCRIPT PENCARIAN OTOMATIS -->
<script>
    let searchTimeout;

    function autoSearch() {
        // Hapus timer sebelumnya agar tidak double submit
        clearTimeout(searchTimeout);

        // Tunggu 800ms (0.8 detik) setelah user berhenti mengetik
        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 800);
    }
    
    // Fitur tambahan: Fokus otomatis ke input setelah refresh (UX)
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchInput');
        // Jika ada nilai search di URL, kembalikan fokus ke input dan taruh kursor di akhir
        if(searchInput.value) {
            searchInput.focus();
            const val = searchInput.value; 
            searchInput.value = ''; 
            searchInput.value = val;
        }
    });
</script>

@endsection