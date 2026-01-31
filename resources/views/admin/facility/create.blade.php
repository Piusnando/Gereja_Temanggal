@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Input Pemakaian Gedung</h2>
    
    <form action="{{ route('admin.facility-bookings.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Fasilitas</label>
            <select name="facility_name" class="w-full border rounded p-2.5 focus:ring-2 focus:ring-green-500">
                <option value="Gereja Utama">Gereja Utama</option>
                <option value="Aula Paroki">Aula Paroki</option>
                <option value="Ruang Rapat">Ruang Rapat Dewan</option>
                <option value="Halaman Parkir">Halaman Parkir</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Peminjam (Lingkungan/Kelompok)</label>
            <input type="text" name="booked_by" class="w-full border rounded p-2.5" placeholder="Contoh: Lingkungan St. Petrus" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Kegiatan / Keperluan</label>
            <input type="text" name="purpose" class="w-full border rounded p-2.5" placeholder="Contoh: Latihan Koor Natal" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Mulai</label>
                <input type="datetime-local" name="start_time" class="w-full border rounded p-2.5" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Selesai</label>
                <input type="datetime-local" name="end_time" class="w-full border rounded p-2.5" required>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.facility-bookings.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded">Batal</a>
            <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">Simpan Jadwal</button>
        </div>
    </form>
</div>
@endsection