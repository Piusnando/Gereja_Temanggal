@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Jadwal Misa & Penugasan</h1>
    <a href="{{ route('admin.liturgy.schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Buat Jadwal Baru
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($schedules as $schedule)
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition border border-gray-200 flex flex-col">
        <div class="p-5 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">{{ $schedule->title }}</h3>
            <div class="flex items-center text-sm text-gray-600 mt-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ $schedule->event_at->translatedFormat('l, d F Y') }}
            </div>
            <div class="flex items-center text-sm text-gray-600 mt-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pukul {{ $schedule->event_at->format('H:i') }} WIB
            </div>
        </div>
        
        <div class="p-5 grow">
            <p class="text-sm text-gray-500 mb-2">Petugas terdaftar:</p>
            <div class="flex flex-wrap gap-2">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    {{ $schedule->assignments->count() }} Orang
                </span>
            </div>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50 text-center">
            <a href="{{ route('admin.liturgy.assign', $schedule->id) }}" class="inline-block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded">
                Atur Petugas
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-10 text-gray-500">
        Belum ada jadwal misa yang dibuat.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $schedules->links() }}
</div>
@endsection