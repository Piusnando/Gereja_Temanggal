@extends('layouts.admin')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.youth.events', $categoryUrl) }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 mb-2 block">← Kembali ke Jadwal</a>
            <h1 class="text-2xl font-bold text-gray-800">Lembar Absensi: {{ $event->title }}</h1>
            <p class="text-sm text-logo-red font-bold">{{ $event->event_date->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    @if(session('success')) <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div> @endif

    <form action="{{ route('admin.youth.attendance.store',['category' => $categoryUrl, 'id' => $event->id]) }}" method="POST" class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-blue-800 text-white uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Anggota</th>
                        <th class="px-6 py-4 text-center">Hadir</th>
                        <th class="px-6 py-4 text-center">Izin</th>
                        <th class="px-6 py-4 text-center">Sakit</th>
                        <th class="px-6 py-4 text-center">Alpa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($members as $m)
                    @php
                        $att = $m->attendances->first();
                        $status = $att ? $att->status : 'Alpa'; // Default Alpa jika belum diabsen
                    @endphp
                    <tr class="hover:bg-blue-50">
                        <td class="px-6 py-3 font-bold text-gray-800">{{ $m->name }}</td>
                        <td class="px-6 py-3 text-center"><input type="radio" name="attendances[{{ $m->id }}]" value="Hadir" {{ $status == 'Hadir' ? 'checked' : '' }} class="w-5 h-5 text-green-600"></td>
                        <td class="px-6 py-3 text-center"><input type="radio" name="attendances[{{ $m->id }}]" value="Izin" {{ $status == 'Izin' ? 'checked' : '' }} class="w-5 h-5 text-blue-600"></td>
                        <td class="px-6 py-3 text-center"><input type="radio" name="attendances[{{ $m->id }}]" value="Sakit" {{ $status == 'Sakit' ? 'checked' : '' }} class="w-5 h-5 text-yellow-500"></td>
                        <td class="px-6 py-3 text-center"><input type="radio" name="attendances[{{ $m->id }}]" value="Alpa" {{ $status == 'Alpa' ? 'checked' : '' }} class="w-5 h-5 text-red-600"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-gray-50 border-t flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg">Simpan Absensi</button>
        </div>
    </form>
</div>
@endsection