@extends('layouts.main')
@section('title', 'Jadwal ' . $role)
@section('content')

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-logo-blue font-bold tracking-widest uppercase text-sm">Jadwal Tugas Liturgi</span>
            <h1 class="text-4xl font-extrabold text-gray-900 mt-2">{{ $role }}</h1>
        </div>

        @forelse($schedules as $schedule)
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border-l-4 border-logo-red flex flex-col md:flex-row">
            
            <!-- Tanggal -->
            <div class="bg-gray-100 p-6 flex flex-col justify-center items-center md:w-40 text-center border-r border-gray-200">
                <span class="text-sm font-bold text-gray-500 uppercase">{{ $schedule->event_at->translatedFormat('F') }}</span>
                <span class="text-4xl font-black text-gray-800 my-1">{{ $schedule->event_at->format('d') }}</span>
                <span class="text-sm font-bold text-gray-500">{{ $schedule->event_at->translatedFormat('l') }}</span>
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-2 font-bold">{{ $schedule->event_at->format('H:i') }}</span>
            </div>

            <!-- Detail -->
            <div class="p-6 grow">
                <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $schedule->title }}</h2>
                
                <div class="space-y-3">
                @foreach($schedule->assignments as $assign)
                <div class="flex items-center p-3 bg-blue-50/50 rounded-lg border border-blue-100 hover:bg-blue-100 transition">
                    
                    <!-- Ikon -->
                    <div class="bg-white p-2 rounded-full text-logo-blue mr-4 shadow-sm shrink-0">
                        @if(in_array($assign->role, ['Paduan Suara', 'Parkir']))
                            <!-- Ikon Group -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @else
                            <!-- Ikon User -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        @endif
                    </div>

                    <!-- Detail Nama -->
                    <div>
                        @if(in_array($role, ['Paduan Suara', 'Parkir']))
                            {{-- LOGIKA BARU UNTUK KELOMPOK --}}
                            @if($assign->lingkungan)
                                <!-- Internal -->
                                <p class="font-bold text-gray-900 text-lg leading-tight">{{ $assign->lingkungan->name }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wide mt-1">
                                    Tugas Wilayah
                                </span>
                            @elseif($assign->description)
                                <!-- Eksternal -->
                                <p class="font-bold text-gray-900 text-lg leading-tight">{{ $assign->description }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-800 uppercase tracking-wide mt-1">
                                    Dari Luar Paroki
                                </span>
                            @else
                                <!-- Data Kosong -->
                                <p class="font-bold text-gray-400 italic">Data Tidak Ditemukan</p>
                            @endif

                        @else
                            {{-- LOGIKA UNTUK PERORANGAN (Misdinar, Lektor, dll) --}}
                            <p class="font-bold text-gray-900 text-lg leading-tight">
                                {{ $assign->personnel->name ?? 'Nama Terhapus' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $assign->personnel->is_external 
                                    ? 'Luar: ' . $assign->personnel->external_description 
                                    : ($assign->personnel->lingkungan->name ?? '-') 
                                }}
                            </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-xl shadow-sm">
            <h3 class="text-gray-500 font-medium">Belum ada jadwal untuk petugas {{ $role }} dalam waktu dekat.</h3>
        </div>
        @endforelse
    </div>
</div>
@endsection