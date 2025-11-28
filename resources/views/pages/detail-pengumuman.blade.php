@extends('layouts.main')

@section('title', $announcement->title . ' - Gereja St. Ignatius Loyola')
@section('header', '')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    
    <!-- HEADER GAMBAR (Opsional, jika ada foto) -->
    @if($announcement->image_path)
        <div class="w-full h-[300px] md:h-[400px] relative">
            <div class="absolute inset-0 bg-black/50 z-10"></div> <!-- Overlay -->
            <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                 class="w-full h-full object-cover">
            
            <div class="absolute inset-0 z-20 flex items-center justify-center">
                <div class="max-w-4xl px-4 text-center text-white">
                    <span class="inline-block py-1 px-3 rounded-full bg-logo-red text-xs font-bold uppercase tracking-wider mb-3">
                        {{ $announcement->category }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-bold leading-tight drop-shadow-lg">
                        {{ $announcement->title }}
                    </h1>
                </div>
            </div>
        </div>
    @else
        <!-- Header Polos jika tidak ada gambar -->
        <div class="bg-logo-blue pt-32 pb-16 text-center text-white px-4">
            <span class="inline-block py-1 px-3 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-3">
                {{ $announcement->category }}
            </span>
            <h1 class="text-3xl md:text-4xl font-bold max-w-4xl mx-auto">
                {{ $announcement->title }}
            </h1>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- KOLOM UTAMA (KONTEN) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm p-6 md:p-10 border border-gray-100">
                    
                    <!-- Meta Data -->
                    <div class="flex items-center text-gray-500 text-sm mb-6 border-b border-gray-100 pb-4">
                        <div class="flex items-center mr-6">
                            <svg class="w-5 h-5 mr-2 text-logo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tanggal Acara: <span class="font-bold ml-1 text-gray-700">{{ $announcement->event_date->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-logo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Diposting: {{ $announcement->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <!-- Isi Konten -->
                    <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">
                        <!-- nl2br agar enter/baris baru terbaca -->
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                    <!-- Tombol Kembali -->
                    <div class="mt-10 pt-6 border-t border-gray-100">
                        <a href="/pengumuman" class="inline-flex items-center text-logo-blue font-bold hover:underline">
                            ← Kembali ke Arsip Pengumuman
                        </a>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR (BERITA LAIN) -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 border-l-4 border-logo-red pl-3">
                        Pengumuman Lainnya
                    </h3>

                    <div class="space-y-6">
                        @foreach($others as $item)
                        <a href="{{ route('pengumuman.detail', $item->id) }}" class="group flex gap-4 items-start">
                            <!-- Thumbnail Kecil -->
                            <div class="w-20 h-20 shrink-0 rounded-lg overflow-hidden bg-gray-200">
                                <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/150' }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </div>
                            
                            <!-- Teks -->
                            <div>
                                <span class="text-[10px] font-bold text-logo-blue uppercase block mb-1">
                                    {{ $item->category }}
                                </span>
                                <h4 class="text-sm font-bold text-gray-800 leading-snug group-hover:text-logo-red transition line-clamp-2">
                                    {{ $item->title }}
                                </h4>
                                <span class="text-xs text-gray-400 mt-1 block">
                                    {{ $item->event_date->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </a>
                        @endforeach

                        @if($others->isEmpty())
                            <p class="text-sm text-gray-500">Tidak ada pengumuman lain.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection