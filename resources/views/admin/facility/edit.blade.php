@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Jadwal Gedung</h2>
    
    {{-- 
        LOGIKA PHP: 
        Hanya masukkan Wilayah dan Lingkungan ke array pengecekan.
        Jika di database tersimpan "OMK", dia tidak akan ketemu di array ini,
        sehingga isManualDefault akan menjadi TRUE (Input text muncul).
    --}}
    @php
        $existingValues = [];
        // Loop organisasi DIHAPUS
        foreach($wilayahs as $w) $existingValues[] = "Wilayah " . $w->name;
        foreach($lingkungans as $l) $existingValues[] = "Lingkungan " . $l->name;
        
        $isManualDefault = !in_array($booking->booked_by, $existingValues);
    @endphp

    <form action="{{ route('admin.facility-bookings.update', $booking->id) }}" method="POST" x-data="{ isManual: {{ $isManualDefault ? 'true' : 'false' }} }">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Fasilitas</label>
            <select name="facility_name" class="w-full border rounded p-2.5 focus:ring-2 focus:ring-green-500 bg-white">
                @foreach(['Gedung Gereja', 'Teras Barat', 'Teras Timur', 'Lapangan Parkir'] as $facility)
                    <option value="{{ $facility }}" {{ $booking->facility_name == $facility ? 'selected' : '' }}>
                        {{ $facility }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Peminjam (Lingkungan/Kelompok)</label>
            
            <!-- Dropdown -->
            <select name="booked_by" 
                    class="w-full border rounded p-2.5 bg-white mb-2"
                    x-show="!isManual"
                    @change="if($event.target.value === 'manual') isManual = true">
                
                <option value="" disabled>-- Pilih Peminjam --</option>

                <!-- GROUP ORGANISASI DIHAPUS -->

                <optgroup label="Wilayah">
                    @foreach($wilayahs as $wil)
                        @php $val = "Wilayah " . $wil->name; @endphp
                        <option value="{{ $val }}" {{ $booking->booked_by == $val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </optgroup>

                <optgroup label="Lingkungan">
                    @foreach($lingkungans as $ling)
                        @php $val = "Lingkungan " . $ling->name; @endphp
                        <option value="{{ $val }}" {{ $booking->booked_by == $val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </optgroup>

                <option value="manual" class="font-bold text-blue-600">+ Lainnya / Ketik Manual</option>
            </select>

            <!-- Input Manual -->
            <div x-show="isManual" class="flex gap-2">
                <input type="text" name="booked_by_text" 
                       value="{{ $isManualDefault ? $booking->booked_by : '' }}"
                       class="w-full border rounded p-2.5 focus:ring-2 focus:ring-green-500" 
                       placeholder="Contoh: OMK, Misdinar..."
                       :required="isManual">
                
                <button type="button" @click="isManual = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 rounded font-bold text-sm">
                    Batal
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Kegiatan / Keperluan</label>
            <input type="text" name="purpose" value="{{ old('purpose', $booking->purpose) }}" class="w-full border rounded p-2.5" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Mulai</label>
                <input type="datetime-local" name="start_time" 
                       value="{{ $booking->start_time->format('Y-m-d\TH:i') }}" 
                       class="w-full border rounded p-2.5" required>
            </div>
            <div>
                <!-- PERUBAHAN DI SINI: Tambahkan label opsional -->
                <label class="block text-sm font-bold text-gray-700 mb-1">Selesai <span class="text-xs font-normal text-gray-500">(Opsional)</span></label>
                
                <!-- PERUBAHAN DI SINI: Hapus 'required' dan tambahkan logic untuk nilai kosong -->
                <input type="datetime-local" name="end_time" 
                       value="{{ $booking->end_time ? $booking->end_time->format('Y-m-d\TH:i') : '' }}" 
                       class="w-full border rounded p-2.5">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.facility-bookings.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded">Batal</a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Update Jadwal</button>
        </div>
    </form>
</div>
@endsection