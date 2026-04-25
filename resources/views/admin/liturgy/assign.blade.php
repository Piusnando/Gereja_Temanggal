@extends('layouts.admin')

@section('content')

{{-- HELPER PHP UNTUK FORMATTING DATA JSON KE ALPINE.JS --}}
@php
    $formatData = function($items) {
        return $items->map(function($p) {
            $asal = $p->is_external ? 'Luar: ' . $p->external_description : ($p->lingkungan->name ?? 'Lintas Lingkungan');
            return['id' => $p->id, 'name' => $p->name, 'asal' => $asal];
        })->values()->toJson();
    };
@endphp

<div class="flex flex-col md:flex-row gap-6">
    
    <!-- ============================================= -->
    <!-- BAGIAN KIRI: FORM INPUT PETUGAS -->
    <!-- ============================================= -->
    <div class="w-full md:w-1/3">
        
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-6" 
            x-data="{ 
                role: '{{ $roles[0] ?? '' }}', 
                selectedWilayah: '', 
                territoriesData: {{ \Illuminate\Support\Js::from($territories) }},
                inputMode: 'existing',

                // FUNGSI INIT: Memantau perubahan Role
                init() {
                    this.$watch('role', (value) => {
                        this.inputMode = 'existing';
                        this.selectedWilayah = ''; 
                        // Memancarkan event untuk mereset kolom pencarian saat ganti peran
                        window.dispatchEvent(new CustomEvent('reset-search'));
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
                <div class="bg-red-50 text-red-700 p-3 rounded-lg text-sm mb-4 border border-red-200 flex items-start">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm mb-4 border border-green-200 flex items-start">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.liturgy.assign.store', $schedule->id) }}" method="POST">
                @csrf
                
                <!-- 1. PILIH PERAN -->
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Peran / Tugas</label>
                    <select name="role" x-model="role" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-semibold text-gray-800">
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
                <!-- MODE 1: PILIH DARI DATABASE (SEARCHABLE) -->
                <!-- ============================================= -->
                <div x-show="inputMode === 'existing'" x-transition>
                    
                    <div class="space-y-4">
                        <!-- Komponen Pencarian Misdinar -->
                        <div x-show="role === 'Misdinar'">
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Cari Nama Misdinar</label>
                            <div x-data="searchableSelect({!! $formatData($misdinars) !!})" @reset-search.window="reset()">
                                @include('admin.liturgy.partials.search_dropdown')
                                <input type="hidden" name="personnel_id" x-model="selectedId" :disabled="role !== 'Misdinar'">
                            </div>
                        </div>

                        <!-- Komponen Pencarian Lektor -->
                        <div x-show="role === 'Lektor'">
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Cari Nama Lektor</label>
                            <div x-data="searchableSelect({!! $formatData($lektors) !!})" @reset-search.window="reset()">
                                @include('admin.liturgy.partials.search_dropdown')
                                <input type="hidden" name="personnel_id" x-model="selectedId" :disabled="role !== 'Lektor'">
                            </div>
                        </div>

                        <!-- Komponen Pencarian Mazmur -->
                        <div x-show="role === 'Mazmur'">
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Cari Nama Pemazmur</label>
                            <div x-data="searchableSelect({!! $formatData($mazmurs) !!})" @reset-search.window="reset()">
                                @include('admin.liturgy.partials.search_dropdown')
                                <input type="hidden" name="personnel_id" x-model="selectedId" :disabled="role !== 'Mazmur'">
                            </div>
                        </div>

                        <!-- Komponen Pencarian Organis -->
                        <div x-show="role === 'Organis'">
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Cari Nama Organis</label>
                            <div x-data="searchableSelect({!! $formatData($organis) !!})" @reset-search.window="reset()">
                                @include('admin.liturgy.partials.search_dropdown')
                                <input type="hidden" name="personnel_id" x-model="selectedId" :disabled="role !== 'Organis'">
                            </div>
                        </div>
                    </div>

                    <!-- B. TUGAS KELOMPOK (PADUS/PARKIR) -->
                    <template x-if="['Paduan Suara', 'Parkir'].includes(role)">
                        <div class="space-y-4 bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <div>
                                <label class="block text-xs font-bold uppercase text-blue-700 mb-1">Filter Wilayah</label>
                                <select x-model="selectedWilayah" class="w-full border border-blue-300 rounded-lg p-3 bg-white focus:ring-2 focus:ring-blue-500 shadow-sm">
                                    <option value="">-- Pilih Wilayah --</option>
                                    <template x-for="wilayah in territoriesData" :key="wilayah.id">
                                        <option :value="wilayah.id" x-text="wilayah.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-blue-700 mb-1">Pilih Lingkungan</label>
                                <select name="lingkungan_id" class="w-full border border-blue-300 rounded-lg p-3 bg-white disabled:bg-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500" 
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
                <div x-show="inputMode === 'new'" class="space-y-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200 mt-4" x-transition>
                    <div>
                        <label class="block text-xs font-bold uppercase text-yellow-700 mb-1">Nama Kelompok</label>
                        <input type="text" name="new_name" class="w-full border border-yellow-300 rounded-lg p-3 focus:ring-2 focus:ring-yellow-500 shadow-sm" placeholder="Contoh: Padus Univ. Atma Jaya">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-yellow-700 mb-1">Asal (Paroki / Instansi)</label>
                        <input type="text" name="new_description" class="w-full border border-yellow-300 rounded-lg p-3 focus:ring-2 focus:ring-yellow-500 shadow-sm" placeholder="Contoh: Paroki Nandan">
                    </div>
                </div>

                <button class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition flex items-center justify-center transform hover:-translate-y-0.5">
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
        @include('admin.liturgy.partials.table_assign') 
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPT KOMPONEN PENCARIAN (ALPINE.JS) -->
<!-- ========================================== -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchableSelect', (optionsData) => ({
            options: optionsData,
            search: '',
            selectedId: '',
            isOpen: false,

            get filteredOptions() {
                if (this.search === '') {
                    return this.options;
                }
                const lowerSearch = this.search.toLowerCase();
                return this.options.filter(opt => 
                    opt.name.toLowerCase().includes(lowerSearch) || 
                    opt.asal.toLowerCase().includes(lowerSearch)
                );
            },

            selectOption(opt) {
                this.selectedId = opt.id;
                this.search = opt.name; // Ganti teks pencarian dengan nama terpilih
                this.isOpen = false;
            },

            reset() {
                this.search = '';
                this.selectedId = '';
                this.isOpen = false;
            }
        }));
    });
</script>
@endsection