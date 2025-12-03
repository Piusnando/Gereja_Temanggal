@extends('layouts.admin')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Jadwal Misa</h2>
    
    <form action="{{ route('admin.liturgy.schedules.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Wajib untuk Update --}}

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2 text-gray-700">Nama Kegiatan / Misa</label>
            <input type="text" name="title" value="{{ old('title', $schedule->title) }}" class="w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold mb-2 text-gray-700">Tanggal & Jam</label>
            <!-- Format DateTime Local harus Y-m-d\TH:i -->
            <input type="datetime-local" name="event_at" value="{{ $schedule->event_at->format('Y-m-d\TH:i') }}" class="w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.liturgy.schedules') }}" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded font-bold text-gray-700 transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded font-bold text-white transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection