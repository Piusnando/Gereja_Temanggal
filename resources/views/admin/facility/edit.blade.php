@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Jadwal Gedung</h2>
    
    <form action="{{ route('admin.facility-bookings.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Wajib untuk Update -->
        
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Fasilitas</label>
            <select name="facility_name" class="w-full border rounded p-2.5 focus:ring-2 focus:ring-green-500">
                @foreach(['Gereja Utama', 'Aula Paroki', 'Ruang Rapat', 'Halaman Parkir'] as $facility)
                    <option value="{{ $facility }}" {{ $booking->facility_name == $facility ? 'selected' : '' }}>
                        {{ $facility }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Peminjam (Lingkungan/Kelompok)</label>
            <input type="text" name="booked_by" value="{{ old('booked_by', $booking->booked_by) }}" class="w-full border rounded p-2.5" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Kegiatan / Keperluan</label>
            <input type="text" name="purpose" value="{{ old('purpose', $booking->purpose) }}" class="w-full border rounded p-2.5" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Mulai</label>
                <!-- Format Tanggal untuk HTML5 datetime-local: Y-m-d\TH:i -->
                <input type="datetime-local" name="start_time" 
                       value="{{ $booking->start_time->format('Y-m-d\TH:i') }}" 
                       class="w-full border rounded p-2.5" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Selesai</label>
                <input type="datetime-local" name="end_time" 
                       value="{{ $booking->end_time->format('Y-m-d\TH:i') }}" 
                       class="w-full border rounded p-2.5" required>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.facility-bookings.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded">Batal</a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Update Jadwal</button>
        </div>
    </form>
</div>
@endsection