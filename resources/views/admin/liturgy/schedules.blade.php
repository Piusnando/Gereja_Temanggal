@extends('layouts.admin')

@section('content')

{{-- 1. HEADER HALAMAN --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Misa & Penugasan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola jadwal peribadatan dan atur petugas liturgi.</p>
    </div>
    
    {{-- Tombol Tambah: Hanya untuk Admin, Pengurus, dan Dir. Musik --}}
    @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'direktur_musik']))
    <a href="{{ route('admin.liturgy.schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Jadwal Baru
    </a>
    @endif
</div>

{{-- 2. FLASH MESSAGE (Sukses/Error) --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center" role="alert">
    <div>
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">×</button>
</div>
@endif

{{-- 3. GRID JADWAL --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    @forelse($schedules as $schedule)
    <!-- 1. Tambahkan 'h-full' agar tinggi kartu mengisi grid sampai bawah -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 border border-gray-200 flex flex-col h-full relative group overflow-hidden">
        
        <!-- A. HEADER KARTU -->
        <div class="p-5 border-b border-gray-100 bg-gray-50">
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-bold text-gray-800 w-3/4 leading-tight">
                    {{ $schedule->title }}
                </h3>
                
                <!-- Aksi Edit & Hapus -->
                @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'direktur_musik']))
                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition duration-200">
                    <a href="{{ route('admin.liturgy.schedules.edit', $schedule->id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-100 rounded transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    <form action="{{ route('admin.liturgy.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $schedule->event_at->translatedFormat('l, d F Y') }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pukul <span class="font-bold ml-1">{{ $schedule->event_at->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>
        
        <!-- B. BODY KARTU (Statistik) -->
        <!-- Gunakan 'grow' untuk mendorong footer ke bawah -->
        <div class="p-5 grow flex flex-col justify-center">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Petugas</span>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                    {{ $schedule->assignments->count() }} Terisi
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5">
                @php $percent = min(100, ($schedule->assignments->count() / 6) * 100); @endphp
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        <!-- C. FOOTER KARTU (Tombol Rapih) -->
        <!-- Gunakan 'mt-auto' sebagai jaminan footer di bawah -->
        <div class="p-4 border-t border-gray-100 bg-white mt-auto">
            <a href="{{ route('admin.liturgy.assign', $schedule->id) }}" 
               class="flex items-center justify-center w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2.5 px-4 rounded-lg transition shadow-md hover:shadow-lg gap-2 group-hover:bg-indigo-700">
                
                @if(in_array(Auth::user()->role, ['misdinar', 'lektor']))
                    <!-- Ikon Mata (Lihat) -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span>Lihat & Isi Tugas</span>
                @else
                    <!-- Ikon Edit (Atur) -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Atur Petugas</span>
                @endif
            </a>
        </div>
    </div>
    @empty
    
    {{-- EMPTY STATE --}}
    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
        <div class="inline-block p-4 rounded-full bg-gray-50 text-gray-300 mb-3">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Belum ada jadwal misa</h3>
        <p class="text-gray-500 mb-4">Silakan buat jadwal misa baru untuk mulai mengatur petugas.</p>
        
        @if(in_array(Auth::user()->role, ['admin', 'pengurus_gereja', 'direktur_musik']))
        <a href="{{ route('admin.liturgy.schedules.create') }}" class="text-blue-600 font-bold hover:underline">
            + Buat Jadwal Sekarang
        </a>
        @endif
    </div>
    @endforelse

</div>

{{-- 4. PAGINATION --}}
<div class="mt-8">
    {{ $schedules->links() }}
</div>

@endsection