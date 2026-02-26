@extends('layouts.admin')

@section('title', 'Dashboard Bina Iman & OMK')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Bina Iman</h1>
            <p class="text-sm text-gray-500 mt-1">Monitoring perkembangan PIA, PIR, dan OMK</p>
        </div>
        <div>
            <!-- Tombol Aksi (Opsional, pastikan route-nya ada jika ingin diklik) -->
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow transition flex items-center">
                <a href="{{ route('admin.youth.attendance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Input Presensi
                </a>
            </button>
        </div>
    </div>

    <!-- 1. Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card PIA -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-pink-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Anak (PIA)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['pia'] }}</h3>
                </div>
                <div class="p-2 bg-pink-50 rounded-lg text-pink-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Usia SD kebawah</p>
        </div>

        <!-- Card PIR -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Remaja (PIR)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['pir'] }}</h3>
                </div>
                <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Usia SMP - SMA</p>
        </div>

        <!-- Card OMK -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Muda (OMK)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['omk'] }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Usia 17+ / Kuliah / Kerja</p>
        </div>

        <!-- Card Rata-rata Kehadiran -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
             <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rata-rata Hadir</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">
                        {{ $recentEvents->count() > 0 ? round($recentEvents->avg('attendances_count')) : 0 }}
                    </h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Per Pertemuan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- 2. Grafik Tren Kehadiran (Kolom Kiri - Lebar 2) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik 5 Pertemuan Terakhir</h3>
            
            @if($recentEvents->isEmpty())
                <div class="text-center py-12 text-gray-400 bg-gray-50 rounded-lg">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Belum ada data pertemuan
                </div>
            @else
                <div class="flex items-end space-x-2 sm:space-x-4 h-64 mt-6 pb-2">
                    @php 
                        $max = $recentEvents->max('attendances_count') ?: 1;
                    @endphp

                    @foreach($recentEvents as $event)
                        @php
                            $height = ($event->attendances_count / $max) * 100;
                            // Minimal tinggi 10% agar bar tetap terlihat walau sedikit
                            if($height < 10) $height = 10; 
                        @endphp
                        
                        <div class="flex-1 flex flex-col items-center group relative">
                            <!-- Tooltip (Angka) -->
                            <div class="mb-2 bg-gray-800 text-white text-xs font-bold rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity absolute bottom-full">
                                {{ $event->attendances_count }}
                            </div>
                            
                            <!-- Bar -->
                            <div class="w-full max-w-[50px] bg-blue-100 rounded-t-md relative hover:bg-blue-200 transition-colors duration-300 overflow-hidden" style="height: {{ $height }}%;">
                                <div class="absolute bottom-0 w-full bg-blue-600 hover:bg-blue-700 transition-all" style="height: 100%;"></div>
                            </div>
                            
                            <!-- Label Tanggal -->
                            <p class="text-[10px] sm:text-xs text-gray-500 mt-2 text-center font-semibold">
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M') }}
                            </p>
                            <!-- Label Judul -->
                            <p class="hidden sm:block text-[10px] text-gray-400 text-center truncate w-full px-1">
                                {{ Str::limit($event->title, 12) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 3. Daftar Umat Pasif (Kolom Kanan - Lebar 1) -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-red-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Warning Pasif</h3>
                <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded uppercase">
                    > 3 Bulan Absen
                </span>
            </div>
            
            <div class="overflow-y-auto max-h-[300px] pr-2">
                @if($passiveMembers->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-10 h-10 mx-auto text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-gray-600">Semua umat aktif!</p>
                    </div>
                @else
                    <ul class="space-y-3">
                        @foreach($passiveMembers as $member)
                        <li class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100 hover:shadow-sm transition">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $member->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $member->category }} • {{ $member->lingkungan->name ?? '-' }}
                                </p>
                            </div>
                            <span class="text-red-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>

    <!-- 4. Tabel Top Rajin -->
    <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Top 5 Umat Paling Rajin (All Time)</h3>
            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-bold">Teraktif</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase font-semibold text-gray-500 text-xs">
                    <tr>
                        <th class="px-6 py-3">Peringkat</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Lingkungan</th>
                        <th class="px-6 py-3 text-center">Total Hadir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($topMembers as $index => $member)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-400">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $member->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide
                                {{ $member->category == 'PIA' ? 'bg-pink-100 text-pink-700' : 
                                  ($member->category == 'PIR' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $member->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $member->lingkungan->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold text-xs">
                                {{ $member->attendances_count }}x
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection