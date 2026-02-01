@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800">Tambah Anggota Organisasi</h2>
    
    <form action="{{ route('admin.organization.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              selectedBidang: '', 
              subBidangValue: '' 
          }"
          x-effect="if(selectedBidang === 'Pengurus Harian') subBidangValue = 'Pengurus Harian'">
        
        @csrf
        
        <!-- NAMA -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" required>
        </div>

        <!-- JABATAN -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan</label>
            <input type="text" name="position" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Ketua, Anggota" required>
        </div>

        <!-- DROPDOWN BIDANG -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Bidang Pelayanan</label>
            <select name="bidang" x-model="selectedBidang" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Pilih Bidang --</option>
                @foreach($bidangList as $bidang)
                    <option value="{{ $bidang }}">{{ $bidang }}</option>
                @endforeach
            </select>
        </div>

        <!-- INPUT SUB BIDANG (AUTOCOMPLETE & CONDITIONAL) -->
        <!-- Hanya muncul jika Bidang dipilih DAN bukan Pengurus Harian -->
        <div class="mb-4" x-show="selectedBidang && selectedBidang !== 'Pengurus Harian'">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tim / Sub-Bidang</label>

            <!-- CHECKBOX TAMPILKAN DI MENU -->
        <div class="mb-4 p-4 border rounded bg-yellow-50 border-yellow-200" x-show="selectedBidang && selectedBidang !== 'Pengurus Harian'">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="tampil_di_menu" value="1" class="form-checkbox h-5 w-5 text-yellow-600 rounded">
                <span class="ml-3 text-sm font-medium text-yellow-800">
                    Tampilkan Tim "<span x-text="subBidangValue || '...'"></span>" di Dropdown Menu Utama?
                </span>
            </label>
            <p class="text-xs text-yellow-600 mt-1 ml-8">
                Centang ini untuk membuat halaman khusus tim ini dapat diakses langsung dari Navbar.
            </p>
        </div>
            
            <!-- Input Text dengan Datalist -->
            <input list="sub_bidang_history" 
                   type="text" 
                   name="sub_bidang" 
                   x-model="subBidangValue"
                   class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" 
                   placeholder="Pilih dari riwayat atau ketik baru..." 
                   autocomplete="off">
            
            <!-- Datalist History -->
            <datalist id="sub_bidang_history">
                @foreach($existingSubBidang as $parent => $subs)
                    <!-- Tampilkan opsi hanya jika Bidang induknya cocok dengan yang dipilih di AlpineJS -->
                    <template x-if="selectedBidang === '{{ $parent }}'">
                        @foreach($subs as $item)
                            <option value="{{ $item->sub_bidang }}">
                        @endforeach
                    </template>
                @endforeach
            </datalist>
            
            <p class="text-xs text-gray-500 mt-1">
                * Ketik untuk membuat tim baru, atau pilih dari daftar yang muncul.
            </p>
        </div>

        <!-- HIDDEN INPUT UNTUK PENGURUS HARIAN -->
        <!-- Jika Pengurus Harian, kita paksa nilai sub_bidang terisi otomatis agar tidak error -->
        <input type="hidden" name="sub_bidang" x-bind:value="subBidangValue" x-if="selectedBidang === 'Pengurus Harian'">

        <!-- LINGKUNGAN -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Lingkungan (Opsional)</label>
            <select name="lingkungan_id" class="w-full border rounded p-2 bg-white">
                <option value="">-- Tidak Ada / Lintas Lingkungan --</option>
                @foreach($lingkungans as $ling)
                    <option value="{{ $ling->id }}">{{ $ling->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- FOTO -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Foto Profil (Opsional)</label>
            <input type="file" name="image" class="w-full border rounded p-2 bg-gray-50 text-sm">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.organization.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded font-bold">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Simpan</button>
        </div>
    </form>
</div>
@endsection