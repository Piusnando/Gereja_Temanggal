@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-100">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Anggota Organisasi</h2>
    </div>
    
    <form action="{{ route('admin.organization.update', $member->id) }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              selectedBidang: '{{ $member->bidang }}', 
              subBidangValue: '{{ $member->sub_bidang }}' 
          }"
          x-effect="if(selectedBidang === 'Pengurus Harian') subBidangValue = 'Pengurus Harian'">
        
        @csrf
        @method('PUT')
        
        <!-- NAMA -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ $member->name }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" required>
        </div>

        <!-- JABATAN -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan</label>
            <input type="text" name="position" value="{{ $member->position }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" required>
        </div>

        <!-- DROPDOWN BIDANG -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Bidang Pelayanan</label>
            <select name="bidang" x-model="selectedBidang" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" required>
                @foreach($bidangList as $bidang)
                    <option value="{{ $bidang }}" {{ $member->bidang == $bidang ? 'selected' : '' }}>{{ $bidang }}</option>
                @endforeach
            </select>
        </div>

        <!-- INPUT SUB BIDANG -->
        <div class="mb-4" x-show="selectedBidang && selectedBidang !== 'Pengurus Harian'">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tim / Sub-Bidang</label>
            <input list="sub_bidang_history" type="text" name="sub_bidang" x-model="subBidangValue" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500" autocomplete="off">
            
            <datalist id="sub_bidang_history">
                @foreach($existingSubBidang as $parent => $subs)
                    <template x-if="selectedBidang === '{{ $parent }}'">
                        @foreach($subs as $item)
                            <option value="{{ $item->sub_bidang }}">
                        @endforeach
                    </template>
                @endforeach
            </datalist>
        </div>

        <!-- Hidden Input Fallback -->
        <input type="hidden" name="sub_bidang" x-bind:value="subBidangValue">

        <!-- LINGKUNGAN -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Lingkungan</label>
            <select name="lingkungan_id" class="w-full border rounded p-2 bg-white">
                <option value="">-- Tidak Ada --</option>
                @foreach($lingkungans as $ling)
                    <option value="{{ $ling->id }}" {{ $member->lingkungan_id == $ling->id ? 'selected' : '' }}>{{ $ling->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- FOTO -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Foto Profil</label>
            @if($member->image)
                <img src="{{ asset('storage/' . $member->image) }}" class="h-16 w-16 rounded-full object-cover mb-2 border">
            @endif
            <input type="file" name="image" class="w-full border rounded p-2 bg-gray-50 text-sm">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.organization.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded font-bold">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Update</button>
        </div>
    </form>
</div>
@endsection