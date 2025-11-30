@extends('layouts.admin')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg">
    <h2 class="text-xl font-bold mb-4">
        Input Data {{ $type ?? 'Petugas' }} Baru
    </h2>
    
    <form action="{{ route('admin.liturgy.personnels.store') }}" method="POST">
        @csrf
        
        <!-- Input Type (Hidden jika sudah ada parameter, atau Select jika umum) -->
        <div class="mb-4">
            <label class="block font-bold text-sm mb-1">Jenis Tugas</label>
            @if($type)
                <input type="text" value="{{ $type }}" class="w-full border rounded p-2 bg-gray-100" disabled>
                <input type="hidden" name="type" value="{{ $type }}">
            @else
                <select name="type" class="w-full border rounded p-2" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Misdinar">Misdinar</option>
                    <option value="Lektor">Lektor</option>
                    <option value="Mazmur">Mazmur</option>
                    <option value="Organis">Organis</option>
                </select>
            @endif
        </div>

        <div class="mb-4">
            <label class="block font-bold text-sm mb-1">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4 p-4 border rounded bg-gray-50" x-data="{ external: false }">
            <div class="mb-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_external" value="1" class="form-checkbox" x-model="external">
                    <span class="ml-2 text-sm">Berasal dari Luar Gereja/Paroki?</span>
                </label>
            </div>

            <!-- Jika Internal -->
            <div x-show="!external">
                <label class="block font-bold text-sm mb-1">Pilih Lingkungan</label>
                <select name="lingkungan_id" class="w-full border rounded p-2">
                    <option value="">-- Pilih Lingkungan --</option>
                    @foreach($lingkungans as $ling)
                        <option value="{{ $ling->id }}">{{ $ling->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jika Eksternal -->
            <div x-show="external" class="mt-2">
                <label class="block font-bold text-sm mb-1">Asal (Paroki/Instansi)</label>
                <input type="text" name="external_description" class="w-full border rounded p-2" placeholder="Contoh: Paroki Nandan">
            </div>
        </div>

        <div class="flex justify-between">
            <a href="{{ url()->previous() }}" class="text-gray-500 font-bold py-2">Batal</a>
            <button class="bg-blue-600 text-white px-4 py-2 rounded font-bold">Simpan Data</button>
        </div>
    </form>
</div>
@endsection