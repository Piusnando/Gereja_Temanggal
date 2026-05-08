@extends('layouts.admin')
@section('content')
<div x-data="{ modalOpen: false }">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Kegiatan {{ $dbCategory }}</h1>
            <p class="text-sm text-gray-500">Atur jadwal kegiatan dan input absensi anggota.</p>
        </div>
        <button @click="modalOpen = true" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg shadow">+ Buat Kegiatan</button>
    </div>

    @if(session('success')) <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div> @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <div class="bg-white rounded-xl shadow border border-gray-100 p-6 flex flex-col hover:border-orange-300 transition">
            <div class="mb-4">
                <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded">{{ $event->event_date->format('d M Y, H:i') }}</span>
                <h3 class="text-lg font-bold mt-2">{{ $event->title }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $event->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            
            <a href="{{ route('admin.youth.attendance',['category' => $categoryUrl, 'id' => $event->id]) }}" class="mt-auto block text-center bg-blue-50 text-blue-600 font-bold py-2 rounded hover:bg-blue-600 hover:text-white transition">
                Buka Lembar Absensi →
            </a>
        </div>
        @empty
        <div class="col-span-3 text-center py-10 bg-white rounded-xl text-gray-400 border border-dashed">Belum ada kegiatan.</div>
        @endforelse
    </div>

    <!-- Modal Form -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-xl font-bold mb-4">Buat Jadwal Baru</h3>
            <form action="{{ route('admin.youth.events.store', $categoryUrl) }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="text-xs font-bold">Nama Kegiatan</label><input type="text" name="title" class="w-full border rounded p-2" required></div>
                <div><label class="text-xs font-bold">Waktu</label><input type="datetime-local" name="event_date" class="w-full border rounded p-2" required></div>
                <div><label class="text-xs font-bold">Keterangan Singkat</label><input type="text" name="description" class="w-full border rounded p-2"></div>
                
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-gray-200 rounded font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection