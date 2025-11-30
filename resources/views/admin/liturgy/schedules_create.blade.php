@extends('layouts.admin')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Buat Jadwal Misa Baru</h2>
    <form action="{{ route('admin.liturgy.schedules.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Nama Kegiatan / Misa</label>
            <input type="text" name="title" class="w-full border rounded p-2" placeholder="Contoh: Misa Minggu Biasa II" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-bold mb-2">Tanggal & Jam</label>
            <input type="datetime-local" name="event_at" class="w-full border rounded p-2" required>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.liturgy.schedules') }}" class="bg-gray-300 px-4 py-2 rounded font-bold text-gray-700">Batal</a>
            <button type="submit" class="bg-blue-600 px-4 py-2 rounded font-bold text-white">Simpan Jadwal</button>
        </div>
    </form>
</div>
@endsection