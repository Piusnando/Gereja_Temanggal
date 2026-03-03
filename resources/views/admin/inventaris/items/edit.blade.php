@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Edit Barang Inventaris</h2>
    </div>

    <form action="{{ route('admin.inventaris.items.update', $item->id) }}" method="POST" 
          x-data="{ 
              locations: {{ $locations->toJson() }},
              categories: {{ $categories->toJson() }},
              selectedLocId: '{{ $item->inv_location_id }}',
              selectedCatId: '{{ $item->inv_category_id }}',
              serialNum: '{{ $item->serial_number }}',
              
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
        @method('PUT')

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang</label>
            <input type="text" name="name" value="{{ old('name', $item->name) }}" class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <!-- Dropdown Lokasi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Penyimpanan</label>
                <select name="inv_location_id" x-model="selectedLocId" class="w-full border rounded-lg p-2.5 bg-white" required>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->code }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Kategori -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Barang</label>
                <select name="inv_category_id" x-model="selectedCatId" class="w-full border rounded-lg p-2.5 bg-white" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <!-- Nomor Seri -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Seri / Urut</label>
                <input type="text" name="serial_number" x-model="serialNum" class="w-full border rounded-lg p-2.5 uppercase" required>
            </div>

            <!-- Kondisi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi</label>
                <select name="condition" class="w-full border rounded-lg p-2.5 bg-white" required>
                    @foreach(['Baik', 'Rusak Sedang', 'Rusak Berat'] as $cond)
                        <option value="{{ $cond }}" {{ $item->condition == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- PREVIEW KODE -->
        <div class="mb-6 p-4 bg-gray-800 rounded-lg text-center shadow-inner">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Kode Barang Baru (Jika Berubah)</p>
            <p class="text-2xl font-mono font-bold text-green-400" x-text="finalCode"></p>
            <p class="text-xs text-gray-500 mt-2">Kode lama: <span class="text-gray-300">{{ $item->item_code }}</span></p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi / Keterangan</label>
            <textarea name="description" rows="3" class="w-full border rounded-lg p-2.5">{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.inventaris.items.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">Update Barang</button>
        </div>
    </form>
</div>
@endsection