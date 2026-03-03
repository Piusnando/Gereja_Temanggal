@extends('layouts.admin')

@section('title', 'Tambah Barang Inventaris')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Input Barang Baru</h2>
        <p class="text-sm text-gray-500">Sistem akan membuat kode barang otomatis berdasarkan pilihan Anda.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Alpine Data: Menyimpan array lokasi & kategori dari backend ke JS --}}
    <form action="{{ route('admin.inventaris.items.store') }}" method="POST" 
          x-data="{ 
              locations: {{ $locations->toJson() }},
              categories: {{ $categories->toJson() }},
              selectedLocId: '',
              selectedCatId: '',
              serialNum: '',
              
              // Fungsi mencari kode 4 digit berdasarkan ID yang dipilih
              get locCode() { 
                  let loc = this.locations.find(l => l.id == this.selectedLocId); 
                  return loc ? loc.code : '----'; 
              },
              get catCode() { 
                  let cat = this.categories.find(c => c.id == this.selectedCatId); 
                  return cat ? cat.code : '----'; 
              },
              get finalCode() {
                  let serial = this.serialNum ? this.serialNum.toUpperCase() : 'XXX';
                  return `GSTI_${this.locCode}_${this.catCode}_${serial}`;
              }
          }">
        @csrf

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang</label>
            <input type="text" name="name" class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <!-- Dropdown Lokasi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Penyimpanan</label>
                <select name="inv_location_id" x-model="selectedLocId" class="w-full border rounded-lg p-2.5 bg-white" required>
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->code }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Kategori -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Barang</label>
                <select name="inv_category_id" x-model="selectedCatId" class="w-full border rounded-lg p-2.5 bg-white" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <!-- Nomor Seri Input -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Seri / Urut</label>
                <input type="text" name="serial_number" x-model="serialNum" class="w-full border rounded-lg p-2.5 uppercase" placeholder="Contoh: 001 atau A12" required>
            </div>

            <!-- Kondisi Dropdown -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi Saat Ini</label>
                <select name="condition" class="w-full border rounded-lg p-2.5 bg-white" required>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Sedang">Rusak Sedang</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>
        </div>

        <!-- LIVE PREVIEW KODE BARANG -->
        <div class="mb-6 p-4 bg-gray-800 rounded-lg text-center shadow-inner">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Preview Kode Barang</p>
            <p class="text-2xl font-mono font-bold text-green-400" x-text="finalCode"></p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi / Keterangan (Opsional)</label>
            <textarea name="description" rows="3" class="w-full border rounded-lg p-2.5"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="#" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg">Batal</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">Simpan Barang</button>
        </div>
    </form>
</div>
@endsection