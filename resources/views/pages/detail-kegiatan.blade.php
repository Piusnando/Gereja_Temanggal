@extends('layouts.main')

@section('title', $activity->title . ' - Kegiatan Gereja')
@section('header', '')

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">

    <!-- Header Gambar Full -->
    @if($activity->image_path)
    <div class="w-full h-[350px] md:h-[500px] relative bg-gray-900">
        <img src="{{ asset('storage/' . $activity->image_path) }}" 
             class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-linear-to-t from-gray-900 via-transparent to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12">
            <div class="max-w-7xl mx-auto">
                <span class="bg-logo-red text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-wider mb-3 inline-block">
                    {{ $activity->organizer }}
                </span>
                <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight drop-shadow-lg mb-2">
                    {{ $activity->title }}
                </h1>
                <p class="text-gray-300 font-medium flex items-center mt-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $activity->location }}
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-logo-blue pt-32 pb-16 px-4">
        <div class="max-w-7xl mx-auto">
            <span class="bg-white/20 text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-wider mb-3 inline-block">
                {{ $activity->organizer }}
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">
                {{ $activity->title }}
            </h1>
        </div>
    </div>
    @endif

    <!-- Konten Utama -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Kolom Kiri: Detail & Deskripsi -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    
                    <!-- Info Waktu -->
                    <div class="flex flex-wrap gap-6 mb-8 border-b border-gray-100 pb-6">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Mulai</span>
                            <span class="text-gray-800 font-bold">
                                {{ $activity->start_time->translatedFormat('l, d F Y') }} <br>
                                Pukul {{ $activity->start_time->format('H:i') }} WIB
                            </span>
                        </div>
                        @if($activity->end_time)
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Selesai</span>
                            <span class="text-gray-800 font-bold">
                                {{ $activity->end_time->translatedFormat('l, d F Y') }} <br>
                                Pukul {{ $activity->end_time->format('H:i') }} WIB
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Isi Deskripsi (Artikel) -->
                    <div class="prose max-w-none text-gray-700 leading-relaxed text-justify">
                        <div class="prose-img:rounded-xl prose-img:shadow-lg prose-img:my-6 prose-img:w-full prose-a:text-blue-600">
                            {!! $activity->description !!}
                        </div>

                    </div>

                    <!-- Tombol Kembali -->
                    <div class="mt-10 pt-6 border-t border-gray-100">
                        <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center text-logo-blue font-bold hover:text-logo-red transition">
                            ← Kembali ke Daftar Kegiatan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Kegiatan Lainnya -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-logo-yellow pl-3">
                        Kegiatan Terbaru Lainnya
                    </h3>
                    <div class="space-y-4">
                        @foreach($others as $other)
                        <a href="{{ route('kegiatan.detail', $other->id) }}" class="flex gap-4 group">
                            <div class="w-20 h-20 shrink-0 rounded-lg overflow-hidden bg-gray-200">
                                <img src="{{ $other->image_path ? asset('storage/' . $other->image_path) : 'https://placehold.co/150' }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-800 group-hover:text-logo-blue transition line-clamp-2">
                                    {{ $other->title }}
                                </h4>
                                <span class="text-xs text-gray-500 block mt-1">
                                    {{ $other->start_time->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection