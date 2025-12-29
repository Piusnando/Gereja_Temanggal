@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row gap-6">
    
    <!-- ============================================= -->
    <!-- BAGIAN KIRI: FORM INPUT PETUGAS -->
    <!-- ============================================= -->
    <div class="w-full md:w-1/3">
        
        <!-- 
            INIT ALPINE JS: 
            - role: Default peran yang dipilih (ambil dari array pertama)
            - selectedWilayah: ID wilayah yang sedang dipilih (kosong default)
            - territoriesData: Data wilayah & lingkungan dari Controller dikonversi ke JSON
        -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-6" 
            x-data="{ 
                role: '{{ $roles[0] ?? '' }}', 
                selectedWilayah: '', 
                territoriesData: {{ \Illuminate\Support\Js::from($territories) }},
                inputMode: 'existing',

                // FUNGSI INIT: Memantau perubahan Role
                init() {
                    this.$watch('role', (value) => {
                        // Setiap kali ganti peran, kembalikan ke mode 'existing' (pilih dari database)
                        // Agar form input manual tertutup otomatis
                        this.inputMode = 'existing';
                    });
                }
            }" x-init="init()">
            
            <h2 class="text-xl font-bold text-gray-800 mb-2 border-b pb-2">Tambah Petugas</h2>
            
            <!-- Info Jadwal -->
            <div class="mb-4">
                <span class="text-xs font-bold text-gray-400 uppercase">Jadwal Misa:</span>
                <p class="text-sm font-semibold text-gray-700">{{ $schedule->title }}</p>
                <p class="text-xs text-gray-500">{{ $schedule->event_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
            </div>

            <!-- Alert -->
            @if(session('error'))
                <div class="bg-red-50 text-red-700 p-3 rounded-lg text-sm mb-4 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm mb-4 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.liturgy.assign.store', $schedule->id) }}" method="POST">
                @csrf
                
                <!-- 1. PILIH PERAN -->
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Peran / Tugas</label>
                    <select name="role" x-model="role" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($roles as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- TOGGLE: INPUT BARU / LUAR -->
                <div class="mb-4 bg-yellow-50 p-3 rounded-lg border border-yellow-200" 
                    x-show="['Paduan Suara', 'Parkir'].includes(role)" 
                    x-transition>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_new_external" value="1" class="form-checkbox h-4 w-4 text-yellow-600 rounded" 
                            @change="inputMode = $el.checked ? 'new' : 'existing'"
                            :checked="inputMode === 'new'">
                        <span class="ml-2 text-sm text-yellow-800 font-medium select-none">
                            Kelompok dari Luar Gereja / Paroki?
                        </span>
                    </label>
                </div>
                
                <!-- ============================================= -->
                <!-- MODE 1: PILIH DARI DATABASE (EXISTING) -->
                <!-- ============================================= -->
                <div x-show="inputMode === 'existing'" x-transition>
                    
                    <!-- A. TUGAS PERORANGAN -->
                    <template x-if="['Misdinar', 'Lektor', 'Mazmur', 'Organis'].includes(role)">
                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1" x-text="'Pilih Nama ' + role"></label>
                            <select name="personnel_id" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Petugas --</option>
                                <template x-if="role === 'Misdinar'">
                                    @foreach($misdinars as $p) <option value="{{ $p->id }}">{{ $p->name }} {{ $p->lingkungan ? '('.$p->lingkungan->name.')' : '' }}</option> @endforeach
                                </template>
                                <template x-if="role === 'Lektor'">
                                    @foreach($lektors as $p) <option value="{{ $p->id }}">{{ $p->name }} {{ $p->lingkungan ? '('.$p->lingkungan->name.')' : '' }}</option> @endforeach
                                </template>
                                <template x-if="role === 'Mazmur'">
                                    @foreach($mazmurs as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                                </template>
                                <template x-if="role === 'Organis'">
                                    @foreach($organis as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                                </template>
                            </select>
                        </div>
                    </template>

                    <!-- B. TUGAS KELOMPOK (Padus/Parkir) -->
                    <template x-if="['Paduan Suara', 'Parkir'].includes(role)">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Filter Wilayah</label>
                                <select x-model="selectedWilayah" class="w-full border border-gray-300 rounded-lg p-2.5">
                                    <option value="">-- Pilih Wilayah --</option>
                                    <template x-for="wilayah in territoriesData" :key="wilayah.id">
                                        <option :value="wilayah.id" x-text="wilayah.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Pilih Lingkungan</label>
                                <select name="lingkungan_id" class="w-full border border-gray-300 rounded-lg p-2.5 disabled:bg-gray-100" 
                                        :disabled="selectedWilayah === ''">
                                    <option value="">-- Pilih Lingkungan --</option>
                                    <template x-for="wilayah in territoriesData">
                                        <template x-if="selectedWilayah == wilayah.id">
                                            <optgroup :label="wilayah.name">
                                                <template x-for="lingkungan in wilayah.lingkungans" :key="lingkungan.id">
                                                    <option :value="lingkungan.id" x-text="lingkungan.name"></option>
                                                </template>
                                            </optgroup>
                                        </template>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- ============================================= -->
                <!-- MODE 2: INPUT BARU / LUAR (NEW) -->
                <!-- ============================================= -->
                <div x-show="inputMode === 'new'" class="space-y-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200" x-transition>
                    <div>
                        <label class="block text-xs font-bold uppercase text-yellow-700 mb-1">Nama Lengkap / Nama Kelompok</label>
                        <input type="text" name="new_name" class="w-full border border-yellow-300 rounded-lg p-2.5 focus:ring-2 focus:ring-yellow-500" placeholder="Contoh: Budi Santoso / Padus Univ. Atma Jaya">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-yellow-700 mb-1">Asal (Paroki / Instansi)</label>
                        <input type="text" name="new_description" class="w-full border border-yellow-300 rounded-lg p-2.5 focus:ring-2 focus:ring-yellow-500" placeholder="Contoh: Paroki Nandan">
                    </div>
                    <p class="text-xs text-yellow-600 italic">* Data ini akan otomatis tersimpan ke Database Petugas sebagai "Luar Paroki".</p>
                </div>

                <button class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Simpan Petugas
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================= -->
    <!-- BAGIAN KANAN: TABEL DAFTAR PETUGAS -->
    <!-- ============================================= -->
    <div class="w-full md:w-2/3">
        
        <!-- Panggil file Partial Tabel agar kode lebih rapi -->
        <!-- Pastikan file ini ada di resources/views/admin/liturgy/partials/table_assign.blade.php -->
        @include('admin.liturgy.partials.table_assign') 

    </div>
</div>
@endsection