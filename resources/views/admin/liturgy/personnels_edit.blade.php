@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">
            Edit Data {{ $personnel->type }}
        </h2>
        <p class="text-sm text-gray-500">Perbarui nama atau asal lingkungan petugas.</p>
    </div>
    
    <!-- Alpine JS di Inisialisasi dari Data Lama -->
    <form action="{{ route('admin.liturgy.personnels.update', $personnel->id) }}" method="POST" x-data="{ external: {{ $personnel->is_external ? 'true' : 'false' }} }">
        @csrf
        @method('PUT')
        
        <!-- Input Type -->
        <div class="mb-4">
            <label class="block font-bold text-sm text-gray-700 mb-1">Jenis Tugas</label>
            @if($type)
                <input type="text" value="{{ $type }}" class="w-full border rounded-lg p-3 bg-gray-100 text-gray-600 font-semibold" disabled>
                <input type="hidden" name="type" value="{{ $type }}">
            @else
                <select name="type" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
                    <option value="Misdinar" {{ $personnel->type == 'Misdinar' ? 'selected' : '' }}>Misdinar</option>
                    <option value="Lektor" {{ $personnel->type == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                    <option value="Mazmur" {{ $personnel->type == 'Mazmur' ? 'selected' : '' }}>Mazmur</option>
                    <option value="Organis" {{ $personnel->type == 'Organis' ? 'selected' : '' }}>Organis</option>
                </select>
            @endif
        </div>

        <!-- Input Nama -->
        <div class="mb-4">
            <label class="block font-bold text-sm text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $personnel->name) }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
        </div>

        <!-- Input Lingkungan / Eksternal Dinamis -->
        <div class="mb-6 p-5 border border-blue-100 rounded-xl bg-blue-50">
            <div class="mb-3 border-b border-gray-200 pb-3">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_external" value="1" class="form-checkbox h-5 w-5 text-blue-600 rounded" x-model="external">
                    <span class="ml-3 text-sm font-bold text-blue-800">Berasal dari Luar Gereja / Paroki Lain?</span>
                </label>
            </div>

            <!-- Jika Internal -->
            <div x-show="!external" x-transition>
                <label class="block font-bold text-sm text-gray-700 mb-1">Pilih Lingkungan</label>
                <select name="lingkungan_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 bg-white" :required="!external">
                    <option value="">-- Pilih Lingkungan --</option>
                    @foreach($lingkungans as $ling)
                        <option value="{{ $ling->id }}" {{ $personnel->lingkungan_id == $ling->id ? 'selected' : '' }}>{{ $ling->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jika Eksternal -->
            <div x-show="external" x-transition style="display: none;">
                <label class="block font-bold text-sm text-gray-700 mb-1">Asal (Paroki/Instansi)</label>
                <input type="text" name="external_description" value="{{ old('external_description', $personnel->external_description) }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Paroki Nandan" :required="external">
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.liturgy.personnels', ['type' => $type]) }}" class="px-5 py-3 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-bold transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition shadow-md">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection