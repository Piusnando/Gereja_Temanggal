@extends('layouts.admin')
@section('content')
<div class="flex gap-6">
    <!-- KIRI: Form Input -->
    <div class="w-1/3">
        <div class="bg-white p-6 rounded shadow mb-6" x-data="{ role: 'Misdinar' }">
            <h2 class="text-lg font-bold mb-2">Tambah Petugas</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $schedule->title }}</p>

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded text-sm mb-4 border border-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.liturgy.assign.store', $schedule->id) }}" method="POST">
                @csrf
                
                <!-- PILIH PERAN -->
                <div class="mb-3">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Peran / Tugas</label>
                    <select name="role" x-model="role" class="w-full border rounded p-2">
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

                <!-- 5. PADUAN SUARA & PARKIR (Lingkungan) -->
                <template x-if="['Paduan Suara', 'Parkir'].includes(role)">
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Asal Lingkungan / Wilayah</label>
                        <select name="lingkungan_id" class="w-full border rounded p-2 bg-blue-50">
                            <option value="">-- Pilih Lingkungan --</option>
                            @foreach($lingkungans as $l)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-blue-500 mt-1">*Tugas Kelompok (Bukan Perorangan)</p>
                    </div>
                </template>

                <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-bold">
                    + Simpan Tugas
                </button>
            </form>
        </div>
    </div>

    <!-- KANAN: Tabel List (Sama seperti sebelumnya) -->
    <div class="w-2/3">
        <!-- ... code tabel list petugas ... -->
        @include('admin.liturgy.partials.table_assign') 
        <!-- (Atau copy paste table code dari jawaban sebelumnya) -->
    </div>
</div>
@endsection