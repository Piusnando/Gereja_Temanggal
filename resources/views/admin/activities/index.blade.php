@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Kegiatan</h1>
    <a href="{{ route('admin.activities.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Buat Kegiatan Baru
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
            <tr>
                <th class="px-5 py-3 text-left">Tanggal</th>
                <th class="px-5 py-3 text-left">Judul Kegiatan</th>
                <th class="px-5 py-3 text-left">Penyelenggara</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 text-sm">
            @forelse($activities as $activity)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-5 py-4 whitespace-nowrap">
                    <p class="font-bold">{{ $activity->start_time->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->start_time->format('H:i') }} WIB</p>
                </td>
                <td class="px-5 py-4">
                    <p class="font-semibold">{{ $activity->title }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->location }}</p>
                </td>
                <td class="px-5 py-4">
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2 py-1 rounded-full">{{ $activity->organizer }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.activities.edit', $activity->id) }}" class="text-blue-600 hover:text-blue-900 font-bold">Edit</a>
                        <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kegiatan ini?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                    Belum ada data kegiatan. Silakan buat baru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $activities->links() }}</div>
</div>
@endsection