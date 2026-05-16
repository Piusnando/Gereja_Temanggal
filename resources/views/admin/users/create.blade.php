@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6 text-gray-800">Tambah Pengguna Baru</h2>
    
    <!-- Tampilkan Error Jika Validasi Gagal -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm border border-red-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Alpine.js untuk mengatur form yang dinamis -->
    <form action="{{ route('admin.users.store') }}" method="POST" 
          x-data="{ 
              role: 'koster', 
              territories: {{ \App\Models\Territory::with('lingkungans')->get()->toJson() }}, 
              selectedWilayah: '' 
          }">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2 focus:ring-blue-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2 focus:ring-blue-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" class="w-full border rounded p-2 focus:ring-blue-500" required>
        </div>
        
        <!-- DROPDOWN ROLE -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Role / Hak Akses</label>
            <!-- x-model="role" agar Alpine tahu role apa yang sedang dipilih -->
            <select name="role" x-model="role" class="w-full border rounded p-2 bg-white focus:ring-blue-500">
                <option value="koster">Koster / Sakristian</option>
                <option value="misdinar">Misdinar</option>
                <option value="lektor">Lektor</option>
                <option value="direktur_musik">Direktur Musik</option>
                <option value="pengurus_gereja">Pengurus Gereja</option>
                <option value="omk">OMK (Orang Muda Katolik)</option>
                <option value="pia_pir">PIA / PIR</option>
                <option value="inventaris">Tim Inventaris / Aset</option>
                <option value="ketua_wilayah">Ketua Wilayah</option>
                <option value="ketua_lingkungan">Ketua Lingkungan</option>
                <option value="admin">Admin (Super User)</option>
            </select>
        </div>

        <!-- FORM TAMBAHAN: MUNCUL JIKA KETUA WILAYAH -->
        <div x-show="role === 'ketua_wilayah'" x-transition style="display: none;" class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-200">
            <label class="block text-blue-800 text-sm font-bold mb-2">Ditugaskan di Wilayah:</label>
            <!-- Jika peran ini dipilih, input ini menjadi required -->
            <select name="territory_id" class="w-full border rounded p-2 bg-white" :required="role === 'ketua_wilayah'">
                <option value="">-- Pilih Wilayah --</option>
                <template x-for="t in territories" :key="t.id">
                    <option :value="t.id" x-text="t.name"></option>
                </template>
            </select>
            <p class="text-xs text-blue-600 mt-2">Ketua Wilayah akan memiliki akses ke semua data lingkungan di wilayahnya.</p>
        </div>

        <!-- FORM TAMBAHAN: MUNCUL JIKA KETUA LINGKUNGAN -->
        <div x-show="role === 'ketua_lingkungan'" x-transition style="display: none;" class="mb-6 bg-green-50 p-4 rounded-lg border border-green-200">
            
            <div class="mb-3">
                <label class="block text-green-800 text-sm font-bold mb-2">1. Pilih Wilayah Dulu:</label>
                <!-- Menyimpan pilihan wilayah untuk memfilter lingkungan -->
                <select x-model="selectedWilayah" class="w-full border rounded p-2 bg-white">
                    <option value="">-- Pilih Wilayah --</option>
                    <template x-for="t in territories" :key="t.id">
                        <option :value="t.id" x-text="t.name"></option>
                    </template>
                </select>
            </div>
            
            <div>
                <label class="block text-green-800 text-sm font-bold mb-2">2. Ditugaskan di Lingkungan:</label>
                <!-- Terbuka jika wilayah sudah dipilih -->
                <select name="lingkungan_id" class="w-full border rounded p-2 bg-white" :disabled="!selectedWilayah" :required="role === 'ketua_lingkungan'">
                    <option value="">-- Pilih Lingkungan --</option>
                    <template x-for="t in territories">
                        <template x-if="t.id == selectedWilayah">
                            <template x-for="l in t.lingkungans" :key="l.id">
                                <option :value="l.id" x-text="l.name"></option>
                            </template>
                        </template>
                    </template>
                </select>
            </div>
            <p class="text-xs text-green-700 mt-2">Ketua Lingkungan hanya memiliki akses eksklusif ke datanya sendiri.</p>
        </div>

        <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded font-bold">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Simpan User</button>
        </div>
    </form>
</div>
@endsection