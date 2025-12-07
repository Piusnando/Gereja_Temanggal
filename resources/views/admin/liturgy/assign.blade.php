@extends('layouts.admin')
@section('content')
<div class="flex gap-6">
    <!-- KIRI: Form Input -->
    <div class="w-1/3">
        <div class="bg-white p-6 rounded shadow mb-6" 
            x-data="{ role: '{{ $roles[0] ?? '' }}', isGroupExternal: false }">
            <h2 class="text-lg font-bold mb-2">Tambah Petugas</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $schedule->title }}</p>

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded text-sm mb-4 border border-red-300">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded text-sm mb-4 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.liturgy.assign.store', $schedule->id) }}" method="POST">
                @csrf
                
                <!-- PILIH PERAN -->
                <div class="mb-3">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Peran / Tugas</label>
                    <select name="role" x-model="role" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($roles as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- DROPDOWN DINAMIS BERDASARKAN PERAN -->
                
                <!-- 1. MISDINAR -->
                <template x-if="role === 'Misdinar'">
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nama Misdinar</label>
                        <select name="personnel_id" class="w-full border rounded p-2">
                            <option value="">-- Pilih Misdinar --</option>
                            @foreach($misdinars as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->lingkungan->name ?? 'Luar' }})</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <!-- 2. LEKTOR -->
                <template x-if="role === 'Lektor'">
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nama Lektor</label>
                        <select name="personnel_id" class="w-full border rounded p-2">
                            <option value="">-- Pilih Lektor --</option>
                            @foreach($lektors as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->lingkungan->name ?? 'Luar' }})</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <!-- 3. MAZMUR -->
                <template x-if="role === 'Mazmur'">
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nama Pemazmur</label>
                        <select name="personnel_id" class="w-full border rounded p-2">
                            <option value="">-- Pilih Pemazmur --</option>
                            @foreach($mazmurs as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->lingkungan->name ?? 'Luar' }})</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <!-- 4. ORGANIS -->
                <template x-if="role === 'Organis'">
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nama Organis</label>
                        <select name="personnel_id" class="w-full border rounded p-2">
                            <option value="">-- Pilih Organis --</option>
                            @foreach($organis as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->lingkungan->name ?? 'Luar' }})</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <!-- 5. PADUAN SUARA & PARKIR (UPDATE BAGIAN INI) -->
            <template x-if="['Paduan Suara', 'Parkir'].includes(role)">
                <div class="mb-4 bg-blue-50 p-3 rounded border border-blue-100">
                    
                    <!-- Checkbox Toggle -->
                    <div class="mb-3 flex items-center">
                        <input type="checkbox" id="extGroup" x-model="isGroupExternal" name="is_external_group" value="1" class="w-4 h-4 text-blue-600 rounded">
                        <label for="extGroup" class="ml-2 text-sm font-bold text-gray-700 cursor-pointer">
                            Kelompok dari Luar Gereja/Paroki?
                        </label>
                    </div>

                    <!-- JIKA INTERNAL (Dropdown Lingkungan) -->
                    <div x-show="!isGroupExternal">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Asal Lingkungan / Wilayah</label>
                        <select name="lingkungan_id" class="w-full border rounded p-2 bg-white">
                            <option value="">-- Pilih Lingkungan --</option>
                            @foreach($lingkungans as $l)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- JIKA EKSTERNAL (Input Teks) -->
                    <div x-show="isGroupExternal">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nama Kelompok / Instansi</label>
                        <input type="text" name="external_name" class="w-full border rounded p-2" placeholder="Contoh: Karang Taruna Desa X / Padus Tamu">
                    </div>

                    <p class="text-xs text-blue-500 mt-2">*Tugas Kelompok</p>
                </div>
            </template>

                <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-bold transition">
                    + Simpan Tugas
                </button>
            </form>
        </div>
    </div>

    <!-- KANAN: Tabel List -->
    <div class="w-2/3">
        @include('admin.liturgy.partials.table_assign') 
    </div>
</div>
@endsection