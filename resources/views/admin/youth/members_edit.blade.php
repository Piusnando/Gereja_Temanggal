@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
    <div class="mb-8 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Edit Anggota {{ $dbCategory }}</h2>
        <p class="text-sm text-gray-500">Perbarui informasi dan data diri anggota.</p>
    </div>

    <form action="{{ route('admin.youth.members.update', ['category' => $categoryUrl, 'id' => $member->id]) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        
        <!-- Baris 1: Nama -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Baptis <span class="text-xs font-normal text-gray-500">(Opsional)</span></label>
                <input type="text" name="baptism_name" value="{{ old('baptism_name', $member->baptism_name) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
        </div>

        <!-- Baris 2: TTL -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tempat Lahir <span class="text-xs font-normal text-gray-500">(Opsional)</span></label>
                <input type="text" name="birth_place" value="{{ old('birth_place', $member->birth_place) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Lahir <span class="text-xs font-normal text-gray-500">(Opsional)</span></label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $member->birth_date ? $member->birth_date->format('Y-m-d') : '') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
        </div>

        <!-- Baris 3: Alamat -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap <span class="text-xs font-normal text-gray-500">(Opsional)</span></label>
            <input type="text" name="address" value="{{ old('address', $member->address) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
        </div>
        
        <!-- Baris 4: Alpine.js Dependent Dropdown Wilayah & Lingkungan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-blue-50 p-5 rounded-xl border border-blue-100" 
             x-data="{ 
                territories: {{ \Illuminate\Support\Js::from($territories) }},
                selectedWilayah: '{{ old('territory_id', $member->territory_id) }}',
                selectedLingkungan: '{{ old('lingkungan_id', $member->lingkungan_id) }}'
             }">
            <div>
                <label class="block text-sm font-bold text-blue-800 mb-1">Pilih Wilayah <span class="text-xs font-normal text-blue-600">(Opsional)</span></label>
                <select name="territory_id" x-model="selectedWilayah" @change="selectedLingkungan = ''" class="w-full border border-blue-300 rounded-lg p-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">-- Pilih Wilayah --</option>
                    <template x-for="t in territories" :key="t.id">
                        <option :value="t.id" x-text="t.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-blue-800 mb-1">Pilih Lingkungan <span class="text-xs font-normal text-blue-600">(Opsional)</span></label>
                <!-- Select ini ter-disable jika Wilayah belum dipilih -->
                <select name="lingkungan_id" x-model="selectedLingkungan" class="w-full border border-blue-300 rounded-lg p-2.5 bg-white disabled:bg-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" :disabled="!selectedWilayah">
                    <option value="">-- Pilih Lingkungan --</option>
                    <template x-for="t in territories">
                        <template x-if="t.id == selectedWilayah">
                            <optgroup :label="t.name">
                                <template x-for="l in t.lingkungans" :key="l.id">
                                    <option :value="l.id" x-text="l.name" :selected="l.id == selectedLingkungan"></option>
                                </template>
                            </optgroup>
                        </template>
                    </template>
                </select>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.youth.members', $categoryUrl) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-bold shadow-md hover:bg-blue-700 transition transform hover:-translate-y-0.5">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection