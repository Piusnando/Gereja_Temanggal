@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-3">Edit Data Pengguna</h2>
    
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
    
    <!-- Alpine.js untuk mengatur form yang dinamis (Menarik data existing user) -->
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
          x-data="{ 
              role: '{{ old('role', $user->role) }}', 
              territories: {{ \App\Models\Territory::with('lingkungans')->get()->toJson() }}, 
              selectedWilayah: '{{ old('territory_id', $user->territory_id ?? '') }}',
              selectedLingkungan: '{{ old('lingkungan_id', $user->lingkungan_id ?? '') }}'
          }">
        @csrf
        @method('PUT')
        
        <!-- HIDDEN INPUT: Menjembatani territory_id agar selalu terkirim dengan benar -->
        <input type="hidden" name="territory_id" x-bind:value="selectedWilayah">

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2.5 focus:ring-blue-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded p-2.5 focus:ring-blue-500" required>
        </div>
        
        <div class="mb-4 bg-gray-50 p-3 rounded border border-gray-200">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru (Opsional)</label>
            <input type="password" name="password" class="w-full border rounded p-2.5 focus:ring-blue-500" placeholder="Kosongkan jika tidak ingin mengganti password">
        </div>
        
        <!-- DROPDOWN ROLE -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Role / Hak Akses</label>
            <select name="role" x-model="role" @change="if(!['ketua_wilayah', 'ketua_lingkungan'].includes(role)) { selectedWilayah = ''; selectedLingkungan = ''; }" class="w-full border rounded p-2.5 bg-white focus:ring-blue-500 font-semibold text-gray-800">
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
            <select x-model="selectedWilayah" class="w-full border rounded p-2.5 bg-white" :required="role === 'ketua_wilayah'">
                <option value="">-- Pilih Wilayah --</option>
                <template x-for="t in territories" :key="t.id">
                    <option :value="t.id" x-text="t.name" :selected="t.id == selectedWilayah"></option>
                </template>
            </select>
            <p class="text-xs text-blue-600 mt-2">Akses otomatis ke seluruh data lingkungan di wilayah ini.</p>
        </div>

        <!-- FORM TAMBAHAN: MUNCUL JIKA KETUA LINGKUNGAN -->
        <div x-show="role === 'ketua_lingkungan'" x-transition style="display: none;" class="mb-6 bg-green-50 p-4 rounded-lg border border-green-200">
            
            <div class="mb-3">
                <label class="block text-green-800 text-sm font-bold mb-2">1. Pilih Wilayah Dulu:</label>
                <select x-model="selectedWilayah" @change="selectedLingkungan = ''" class="w-full border rounded p-2.5 bg-white">
                    <option value="">-- Pilih Wilayah --</option>
                    <template x-for="t in territories" :key="t.id">
                        <option :value="t.id" x-text="t.name" :selected="t.id == selectedWilayah"></option>
                    </template>
                </select>
            </div>
            
            <div>
                <label class="block text-green-800 text-sm font-bold mb-2">2. Ditugaskan di Lingkungan:</label>
                <!-- Menyimpan pilihan lingkungan ke form (name="lingkungan_id") -->
                <select name="lingkungan_id" x-model="selectedLingkungan" class="w-full border rounded p-2.5 bg-white" :disabled="!selectedWilayah" :required="role === 'ketua_lingkungan'">
                    <option value="">-- Pilih Lingkungan --</option>
                    <template x-for="t in territories">
                        <template x-if="t.id == selectedWilayah">
                            <template x-for="l in t.lingkungans" :key="l.id">
                                <!-- Pre-select jika id cocok -->
                                <option :value="l.id" x-text="l.name" :selected="l.id == selectedLingkungan"></option>
                            </template>
                        </template>
                    </template>
                </select>
            </div>
            <p class="text-xs text-green-700 mt-2">Akses eksklusif hanya untuk data lingkungan terpilih.</p>
        </div>
        
        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-bold transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5">Update User</button>
        </div>
    </form>
</div>
@endsection