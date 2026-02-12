@extends('layouts.main')

@section('title', 'Lingkungan ' . $lingkungan->name)

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">
    
    <!-- HEADER LINGKUNGAN -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row items-center gap-8">
                
                <!-- FOTO SANTO PELINDUNG -->
                <div class="w-40 h-40 shrink-0 rounded-full overflow-hidden border-4 border-logo-blue shadow-xl bg-gray-100 relative group">
                    @if($lingkungan->saint_image)
                        <img src="{{ asset('storage/' . $lingkungan->saint_image) }}" class="w-full h-full object-cover">
                    @else
                        <!-- Placeholder Icon jika belum ada foto -->
                        <div class="w-full h-full flex items-center justify-center bg-blue-50 text-logo-blue">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    @endif
                </div>

                <!-- INFO TEKS -->
                <div class="text-center md:text-left flex-1">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-logo-blue text-xs font-bold uppercase tracking-widest rounded-full mb-2">
                        Wilayah {{ $lingkungan->territory->name ?? '-' }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-2">
                        Lingkungan {{ $lingkungan->name }}
                    </h1>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-6 mt-4 text-sm text-gray-600">
                        <!-- Nama Pelindung -->
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-logo-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            <span class="font-bold">Pelindung:</span>&nbsp; {{ $lingkungan->patron_saint ?? '-' }}
                        </div>
                        
                        <!-- Ketua Lingkungan -->
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-logo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="font-bold">Ketua Lingkungan:</span>&nbsp; {{ $lingkungan->chief_name ?? '-' }}
                        </div>
                    </div>

                    <!-- Info Tambahan -->
                    @if($lingkungan->info)
                        <p class="mt-4 text-gray-500 italic max-w-2xl text-sm">"{{ $lingkungan->info }}"</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR KEGIATAN -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-logo-red pl-3 flex items-center justify-between">
            <span>Agenda & Kegiatan</span>
            <span class="text-xs font-normal bg-gray-200 text-gray-600 px-2 py-1 rounded">Termasuk Kegiatan Paroki</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activities as $activity)
                {{-- Gunakan logika card yang sama seperti di home --}}
                <a href="{{ route('kegiatan.detail', $activity->id) }}" class="group bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden border border-gray-100 flex flex-col h-full">
                    <div class="h-40 overflow-hidden bg-gray-100 relative">
                        <img src="{{ $activity->image_path ? asset('storage/' . $activity->image_path) : 'https://placehold.co/600x400?text=Kegiatan' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <!-- Badge Khusus -->
                        @if($activity->lingkungan_id)
                            <span class="absolute top-2 right-2 bg-green-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow">KHUSUS LINGKUNGAN</span>
                        @else
                            <span class="absolute top-2 right-2 bg-logo-blue text-white text-[10px] font-bold px-2 py-1 rounded shadow">PAROKI (UMUM)</span>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col grow">
                        <span class="text-xs text-gray-500 mb-2 font-mono">{{ $activity->start_time->format('d M Y') }}</span>
                        <h3 class="font-bold text-gray-800 leading-tight mb-2 group-hover:text-logo-red transition">{{ $activity->title }}</h3>
                        <p class="text-xs text-gray-600 line-clamp-2 mb-4 grow">{{ Str::limit(strip_tags($activity->description), 80) }}</p>
                        <span class="text-xs font-bold text-logo-blue flex items-center mt-auto">Detail <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></span>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-10 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500">Belum ada agenda kegiatan untuk saat ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection