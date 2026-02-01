@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Input Pemakaian Gedung</h2>
    
    <form action="{{ route('admin.facility-bookings.store') }}" method="POST" x-data="{ isManual: false }">
        @csrf
        
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Fasilitas</label>
            <select name="facility_name" class="w-full border rounded p-2.5 focus:ring-2 focus:ring-green-500 bg-white">
                <option value="Gedung Gereja">Gedung Gereja</option>
                <option value="Teras Barat">Teras Barat</option>
                <option value="Teras Timur">Teras Timur</option>
                <option value="Lapangan Parkir">Lapangan Parkir</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Peminjam (Lingkungan/Kelompok)</label>
            
            <!-- Dropdown Pilihan -->
            <select name="booked_by" 
                    class="w-full border rounded p-2.5 bg-white mb-2" 
                    x-show="!isManual" 
                    :required="!isManual"
                    @change="if($event.target.value === 'manual') isManual = true">
                
                <option value="" disabled selected>-- Pilih Peminjam --</option>
                
                <!-- GROUP ORGANISASI DIHAPUS (OMK/Misdinar ketik manual) -->

                <!-- GROUP 2: WILAYAH -->
                <optgroup label="Wilayah">
                    @foreach($wilayahs as $wil)
                        <option value="Wilayah {{ $wil->name }}">Wilayah {{ $wil->name }}</option>
                    @endforeach
                </optgroup>

                <!-- GROUP 3: LINGKUNGAN -->
                <optgroup label="Lingkungan">
                    @foreach($lingkungans as $ling)
                        <option value="Lingkungan {{ $ling->name }}">Lingkungan {{ $ling->name }}</option>
                    @endforeach
                </optgroup>

                <!-- OPSI LAINNYA -->
                <option value="manual" class="font-bold text-blue-600">+ Lainnya / Ketik Manual (OMK, dll)</option>
            </select>

            <!-- Input Manual -->
            <div x-show="isManual" x-transition class="flex gap-2">
                <input type="text" name="booked_by_text" 
                       class="w-full border rounded p-2.5 focus:ring-2 focus:ring-green-500" 
                       placeholder="Contoh: OMK, Misdinar, Panitia Natal..."
                       :required="isManual">
                
                <button type="button" @click="isManual = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 rounded font-bold text-sm">
                    Batal
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Kegiatan / Keperluan</label>
            <input type="text" name="purpose" class="w-full border rounded p-2.5" placeholder="Contoh: Latihan Koor, Rapat Dewan" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Mulai</label>
                <input type="datetime-local" name="start_time" class="w-full border rounded p-2.5" required>
            </div>
            <div>
                <!-- PERUBAHAN DI SINI: Tambahkan label opsional -->
                <label class="block text-sm font-bold text-gray-700 mb-1">Selesai <span class="text-xs font-normal text-gray-500">(Opsional)</span></label>
                <!-- PERUBAHAN DI SINI: Hapus 'required' -->
                <input type="datetime-local" name="end_time" class="w-full border rounded p-2.5">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.facility-bookings.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded">Batal</a>
            <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">Simpan Jadwal</button>
        </div>
    </form>
</div>
@endsection