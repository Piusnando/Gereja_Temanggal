@extends('layouts.admin')

@section('content')

<!-- PENGUNCI ALPINE.JS & CUSTOM SCROLLBAR -->
<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<!-- ========================================================================= -->
<!-- DIPERBAIKI: Cara pengiriman data ke Alpine.js diubah agar tidak error    -->
<!-- ========================================================================= -->
<div x-data="liturgyCalendar(
        {{ \Illuminate\Support\Js::from($allSchedules) }},
        {{ \Illuminate\Support\Js::from($personnels) }},
        {{ \Illuminate\Support\Js::from($lingkungans) }}
    )" x-init="initCalendar()">

    <!-- HEADER HALAMAN -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kalender & Penugasan Liturgi</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih tanggal pada kalender untuk mengelola misa dan mengatur petugas.</p>
        </div>
        @if(in_array(Auth::user()->role,['admin', 'pengurus_gereja', 'direktur_musik']))
        <a href="{{ route('admin.liturgy.schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md flex items-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Jadwal Misa Baru
        </a>
        @endif
    </div>

    <!-- NOTIFIKASI -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- KOLOM KIRI: KALENDER INTERAKTIF -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col min-h-[80vh]">
            
            <div class="flex items-center justify-between px-6 py-4 bg-blue-800 text-white">
                <button @click="prevMonth()" class="p-2 rounded-full hover:bg-white/20 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <h2 class="text-xl font-bold uppercase tracking-widest" x-text="monthNames[month] + ' ' + year"></h2>
                <button @click="nextMonth()" class="p-2 rounded-full hover:bg-white/20 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
            </div>

            <div class="grid grid-cols-7 text-center bg-gray-50 border-b border-gray-200">
                <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']" :key="day">
                    <div class="py-3 text-xs font-bold text-gray-500 uppercase" x-text="day"></div>
                </template>
            </div>

            <!-- Grid Tanggal -->
            <div class="grid grid-cols-7 auto-rows-fr bg-gray-200 gap-px grow">
                <template x-for="(blankday, index) in blankDays" :key="`blank-${index}`"><div class="bg-gray-50"></div></template>
                
                <template x-for="date in no_of_days" :key="date">
                    <div @click="openDate(date)" class="bg-white p-2 flex flex-col relative transition duration-200 hover:bg-blue-50 cursor-pointer group min-h-[120px]">
                        
                        <span class="text-sm font-bold w-8 h-8 flex items-center justify-center rounded-full transition-all"
                              :class="isToday(date) ? 'bg-red-600 text-white shadow-md' : 'text-gray-700 group-hover:bg-blue-100'" x-text="date"></span>
                        
                        <div class="mt-1 space-y-1.5 grow overflow-y-auto custom-scrollbar">
                            <template x-for="sched in getSchedulesForDay(date)" :key="sched.id">
                                <div @click.stop="openSchedule(sched)" class="bg-blue-100 border-l-4 border-blue-500 text-blue-800 text-xs px-2 py-1.5 rounded truncate shadow-sm hover:bg-blue-200 transition cursor-pointer">
                                    <span class="font-bold" x-text="formatTime(sched.event_at)"></span> <br><span x-text="sched.title"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- KOLOM KANAN: JADWAL TERDEKAT -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-6">
                <div class="p-5 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-lg font-bold text-gray-800">Jadwal Terdekat</h3>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    @forelse($upcomingSchedules as $sched)
                        <div class="p-5 hover:bg-gray-50 transition relative group cursor-pointer" @click="openScheduleById({{ $sched->id }})">
                            <button class="absolute top-4 right-4 bg-white border border-gray-200 p-2 rounded-lg text-blue-500 hover:text-white hover:bg-blue-600 shadow-md opacity-0 group-hover:opacity-100 transition z-10" title="Kelola Jadwal & Petugas ini">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="bg-blue-100 text-blue-800 px-3 py-1.5 rounded-xl text-center border border-blue-200 shadow-sm shrink-0">
                                    <span class="block text-xl font-black leading-none">{{ $sched->event_at->format('d') }}</span>
                                    <span class="block text-[10px] font-bold uppercase mt-0.5">{{ $sched->event_at->format('M') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-base leading-tight pr-6">{{ $sched->title }}</h4>
                                    <span class="text-xs text-gray-500 font-semibold">{{ $sched->event_at->translatedFormat('l, H:i') }} WIB</span>
                                </div>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-3 space-y-2">
                                @forelse($sched->assignments as $assign)
                                    <div class="flex items-start text-sm border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                                        <span class="text-[10px] font-bold text-blue-600 uppercase w-20 shrink-0 mt-0.5 bg-blue-50 px-1 py-0.5 rounded text-center">{{ $assign->role }}</span>
                                        <span class="text-gray-800 font-medium ml-3 leading-snug">{{ $assign->personnel->name ?? ($assign->lingkungan->name ?? $assign->description) }}</span>
                                    </div>
                                @empty
                                    <div class="text-xs text-red-500 italic text-center py-2 flex items-center justify-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Belum ada petugas</div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 italic">Belum ada jadwal terdekat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL (Kodenya sama persis, tidak perlu diubah) -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
    
    <!-- Background Hitam Transparan -->
    <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" 
         x-show="modalOpen" 
         x-transition.opacity 
         @click="closeModal()"></div>
    
    <!-- Kotak Modal Utama -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-50 flex flex-col max-h-[90vh] overflow-hidden transform transition-all"
         x-show="modalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">
        
        <!-- MODAL HEADER (Dinamic Berdasarkan Step) -->
        <div class="bg-blue-800 p-6 flex justify-between items-center text-white shrink-0">
            
            <!-- Title Step 1 (Pilih Jadwal) -->
            <div x-show="step === 1" style="display: none;">
                <h3 class="text-xl md:text-2xl font-bold">Pilih Jadwal Misa</h3>
                <p class="text-sm text-blue-200 mt-1">Tanggal <span x-text="selectedDateNum"></span> <span x-text="monthNames[month] + ' ' + year"></span></p>
            </div>
            
            <!-- Title Step 2 (Detail Jadwal) -->
            <div x-show="step === 2" class="flex items-center" style="display: none;">
                <button @click="step = 1" class="mr-4 hover:text-blue-200 bg-white/10 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <div>
                    <h3 class="text-xl font-bold truncate max-w-[200px] md:max-w-xs" x-text="selectedSchedule?.title"></h3>
                    <p class="text-sm text-blue-200 mt-1" x-text="formatFullDate(selectedSchedule?.event_at)"></p>
                </div>
            </div>

            <!-- Title Step 3 (Pilih Peran) -->
            <div x-show="step === 3" class="flex items-center" style="display: none;">
                <button @click="step = 2" class="mr-4 hover:text-blue-200 bg-white/10 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <h3 class="text-2xl font-bold">Pilih Peran</h3>
            </div>

            <!-- Title Step 4 (Pilih Orang) -->
            <div x-show="step === 4" class="flex items-center" style="display: none;">
                <button @click="step = 3; searchQuery=''" class="mr-4 hover:text-blue-200 bg-white/10 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <h3 class="text-2xl font-bold">Pilih <span x-text="selectedRole" class="text-yellow-400"></span></h3>
            </div>

            <!-- Tombol X Close Modal -->
            <button @click="closeModal()" class="text-white hover:text-red-400 transition"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>

        <!-- MODAL BODY -->
        <div class="p-6 overflow-y-auto custom-scrollbar bg-gray-50 grow min-h-[400px]">
            
            <!-- STEP 1: DAFTAR JADWAL PADA TANGGAL YANG DIKLIK -->
            <div x-show="step === 1" style="display: none;">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-gray-700 text-lg">Misa yang Tersedia</h4>
                    @if(in_array(Auth::user()->role,['admin', 'pengurus_gereja', 'direktur_musik']))
                    <a href="{{ route('admin.liturgy.schedules.create') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 font-bold py-2 px-4 rounded-lg shadow-sm transition text-sm">
                        + Buat Jadwal Baru
                    </a>
                    @endif
                </div>

                <div class="space-y-3">
                    <template x-for="sched in selectedDateSchedules" :key="sched.id">
                        <div @click="openSchedule(sched)" class="bg-white border border-gray-200 hover:border-blue-400 hover:shadow-md p-5 rounded-xl cursor-pointer transition group flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-bold text-gray-800 group-hover:text-blue-700 transition" x-text="sched.title"></h5>
                                <p class="text-sm text-gray-500 font-mono mt-1">Pukul <span x-text="formatTime(sched.event_at)"></span> WIB</p>
                            </div>
                            <div class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span x-text="sched.assignments.length"></span> Petugas
                            </div>
                        </div>
                    </template>
                    <template x-if="selectedDateSchedules.length === 0">
                        <div class="text-center py-16 bg-white border border-gray-200 rounded-xl text-gray-400 italic">
                            Belum ada jadwal misa pada tanggal ini.
                        </div>
                    </template>
                </div>
            </div>

            <!-- STEP 2: DETAIL JADWAL MISA (EDIT, HAPUS, LIST) -->
            <div x-show="step === 2" style="display: none;">
                
                @if(in_array(Auth::user()->role,['admin', 'pengurus_gereja', 'direktur_musik']))
                <div class="flex flex-wrap justify-end gap-3 mb-6 pb-6 border-b border-gray-200" x-show="selectedSchedule">
                    <!-- Tombol Edit Jadwal -->
                    <a :href="`/admin/liturgy/schedules/${selectedSchedule?.id}/edit`" class="bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center transition border border-yellow-200 hover:border-transparent shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit Info Misa
                    </a>
                    
                    <!-- Tombol Hapus Jadwal -->
                    <form :action="`/admin/liturgy/schedules/${selectedSchedule?.id}`" method="POST" onsubmit="return confirm('PERINGATAN!\n\nMenghapus jadwal ini akan MENGHAPUS SEMUA DATA PETUGAS di Misa ini.\n\nYakin hapus?');" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-500 hover:text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center transition border border-red-200 hover:border-transparent shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus Jadwal
                        </button>
                    </form>
                </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-gray-700 text-lg">Petugas Bertugas</h4>
                    @if(!empty($allowedRoles))
                    <button @click="step = 3" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg> 
                        Tambah Petugas
                    </button>
                    @endif
                </div>

                <!-- List Petugas Terdaftar -->
                <template x-if="selectedSchedule && selectedSchedule.assignments.length > 0">
                    <div class="grid grid-cols-1 gap-3">
                        <template x-for="assign in selectedSchedule.assignments" :key="assign.id">
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex justify-between items-center hover:border-blue-300 transition">
                                <div>
                                    <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded uppercase tracking-wider" x-text="assign.role"></span>
                                    <h5 class="font-bold text-gray-800 text-base mt-1" x-text="assign.personnel ? assign.personnel.name : (assign.lingkungan ? assign.lingkungan.name : assign.description)"></h5>
                                    <p class="text-xs text-gray-500 mt-0.5" x-text="assign.personnel && assign.personnel.lingkungan ? assign.personnel.lingkungan.name : ''"></p>
                                </div>
                                <form :action="`/admin/liturgy/assignments/${assign.id}`" method="POST" onsubmit="return confirm('Hapus petugas ini dari jadwal?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 bg-gray-100 p-2.5 hover:bg-red-100 hover:text-red-600 rounded-lg transition" title="Cabut Tugas"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="selectedSchedule && selectedSchedule.assignments.length === 0">
                    <div class="text-center py-10 bg-white border border-gray-200 rounded-xl text-gray-400 italic">Belum ada petugas yang dijadwalkan untuk misa ini.</div>
                </template>
            </div>

            <!-- STEP 3: PILIH PERAN -->
            <div x-show="step === 3" style="display: none;">
                <p class="text-gray-500 mb-4 text-center">Pilih peran/tugas yang ingin Anda tambahkan:</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($allowedRoles as $role)
                        <button @click="selectRole('{{ $role }}')" class="bg-white border-2 border-gray-200 hover:border-blue-600 hover:shadow-lg p-6 rounded-2xl text-center transition group">
                            <div class="w-14 h-14 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="font-bold text-gray-800 text-sm block">{{ $role }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- STEP 4: PILIH ORANG/LINGKUNGAN & SAVE -->
            <div x-show="step === 4" style="display: none;">
                <div class="relative mb-4">
                    <input type="text" x-model="searchQuery" placeholder="Ketik nama untuk mencari..." class="w-full border border-gray-300 rounded-xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-blue-600 outline-none shadow-sm text-gray-700">
                    <svg class="w-6 h-6 absolute left-4 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <div class="grid grid-cols-1 gap-3 overflow-y-auto max-h-[50vh] pr-2 custom-scrollbar">
                    <template x-for="item in filteredList" :key="item.id">
                        <form :action="`/admin/liturgy/schedules/${selectedSchedule.id}/assign`" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="role" :value="selectedRole">
                            <template x-if="['Paduan Suara', 'Parkir'].includes(selectedRole)"><input type="hidden" name="lingkungan_id" :value="item.id"></template>
                            <template x-if="!['Paduan Suara', 'Parkir'].includes(selectedRole)"><input type="hidden" name="personnel_id" :value="item.id"></template>
                            <button type="submit" class="w-full bg-white border border-gray-200 hover:border-green-500 hover:bg-green-50 p-4 rounded-xl flex justify-between items-center text-left transition group shadow-sm">
                                <div>
                                    <h5 class="font-bold text-gray-800 text-lg leading-tight" x-text="item.name"></h5>
                                    <p class="text-xs font-semibold text-gray-500 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <span x-text="item.asal"></span>
                                    </p>
                                </div>
                                <span class="bg-gray-100 text-gray-600 group-hover:bg-green-500 group-hover:text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-sm">Pilih</span>
                            </button>
                        </form>
                    </template>

                    <template x-if="filteredList.length === 0">
                        <div class="text-center py-10 bg-white rounded-xl border border-gray-200 text-gray-400 italic">Data tidak ditemukan.</div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT LOGIKA ALPINE.JS (PERBAIKAN UTAMA ADA DI SINI) -->
<script>
    function liturgyCalendar(schedules, personnels, lingkungans) {
        return {
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            monthNames:['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            no_of_days: [],
            blankDays:[],
            
            schedules: schedules,
            allPersonnels: personnels,
            allLingkungans: lingkungans,
            
            modalOpen: false, step: 1, selectedDateNum: null, selectedDateSchedules:[], selectedSchedule: null, selectedRole: '', searchQuery: '',

            initCalendar() {
                const firstDay = new Date(this.year, this.month, 1);
                const lastDay = new Date(this.year, this.month + 1, 0);
                const startingDay = firstDay.getDay(); // 0-6 (Minggu-Sabtu)
                const daysInMonth = lastDay.getDate(); // 28-31
                
                // Array.from() adalah cara modern dan aman untuk membuat array
                this.blankDays = Array.from({ length: startingDay });
                this.no_of_days = Array.from({ length: daysInMonth }, (_, i) => i + 1);
            },
            
            isToday(date) { return new Date().toDateString() === new Date(this.year, this.month, date).toDateString(); },
            getSchedulesForDay(date) { return this.schedules.filter(s => { let d = new Date(s.event_at); return d.getDate() === date && d.getMonth() === this.month && d.getFullYear() === this.year; }); },
            formatTime(datetime) { return new Date(datetime).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }); },
            formatFullDate(datetime) { if(!datetime) return ''; return new Date(datetime).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' WIB'; },
            
            prevMonth() { this.month--; if (this.month < 0) { this.month = 11; this.year--; } this.initCalendar(); },
            nextMonth() { this.month++; if (this.month > 11) { this.month = 0; this.year++; } this.initCalendar(); },
            
            openDate(date) { this.selectedDateNum = date; this.selectedDateSchedules = this.getSchedulesForDay(date); this.step = 1; this.modalOpen = true; },
            openSchedule(sched) { this.selectedSchedule = sched; this.step = 2; this.modalOpen = true; },
            openScheduleById(id) { let sched = this.schedules.find(s => s.id === id); if(sched) this.openSchedule(sched); },
            closeModal() { this.modalOpen = false; setTimeout(() => { this.step = 1; this.searchQuery = ''; }, 300); },
            selectRole(role) { this.selectedRole = role; this.searchQuery = ''; this.step = 4; },

            get filteredList() {
                let list =[]; let q = this.searchQuery.toLowerCase();
                if (['Paduan Suara', 'Parkir'].includes(this.selectedRole)) { list = this.allLingkungans.map(l => ({ id: l.id, name: 'Lingkungan ' + l.name, asal: 'Tugas Wilayah' })); } 
                else { list = this.allPersonnels.filter(p => p.type === this.selectedRole).map(p => ({ id: p.id, name: p.name, asal: p.is_external ? 'Luar: ' + p.external_description : (p.lingkungan ? 'Lingk. ' + p.lingkungan.name : 'Internal') })); }
                if(q) return list.filter(item => item.name.toLowerCase().includes(q) || (item.asal && item.asal.toLowerCase().includes(q)));
                return list;
            }
        }
    }
</script>
@endsection