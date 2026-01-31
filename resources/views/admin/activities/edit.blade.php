@extends('layouts.admin')

@section('content')
<!-- Load CSS Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-3">Edit Berita Kegiatan</h2>
    
    <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Judul Berita / Nama Kegiatan</label>
            <input type="text" name="title" value="{{ $activity->title }}" class="w-full border border-gray-300 rounded p-2.5" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Penyelenggara</label>
                <input type="text" name="organizer" value="{{ $activity->organizer }}" class="w-full border border-gray-300 rounded p-2.5" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Kegiatan</label>
                <input type="text" name="location" value="{{ $activity->location }}" class="w-full border border-gray-300 rounded p-2.5" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Waktu Mulai</label>
                <!-- Format Tanggal Wajib Y-m-d\TH:i untuk datetime-local HTML5 -->
                <input type="datetime-local" name="start_time" value="{{ $activity->start_time->format('Y-m-d\TH:i') }}" class="w-full border border-gray-300 rounded p-2.5" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Waktu Selesai (Opsional)</label>
                <input type="datetime-local" name="end_time" value="{{ $activity->end_time ? $activity->end_time->format('Y-m-d\TH:i') : '' }}" class="w-full border border-gray-300 rounded p-2.5">
            </div>
        </div>

        <!-- EDITOR SUMMERNOTE -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Lengkap & Foto Kegiatan</label>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-3 text-sm rounded mb-3">
                <p class="font-bold">Penting:</p>
                <p>Jika menyisipkan foto di dalam teks, pastikan ukurannya **kurang dari 2 MB** untuk menghindari error saat menyimpan.</p>
            </div>
            <textarea id="summernote" name="description" required>{{ $activity->description }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Gambar Sampul</label>
            @if($activity->image_path)
                <div class="mb-2"><img src="{{ asset('storage/' . $activity->image_path) }}" class="h-32 rounded"></div>
            @endif
            <input id="imageInput" type="file" name="image" class="w-full border border-gray-300 rounded p-2 bg-gray-50 text-sm">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ganti. Ukuran file maksimal **2 MB**.</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Update Berita</button>
        </div>
    </form>
</div>

<!-- JS JQuery, Summernote, dan SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Inisialisasi Summernote
    $('#summernote').summernote({
        tabsize: 2,
        height: 400
    });

    // Validasi Ukuran File Sebelum Submit
    document.getElementById('activityForm').addEventListener('submit', function(event) {
        const fileInput = document.getElementById('imageInput');
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB

            if (file.size > maxSizeInBytes) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Terlalu Besar!',
                    text: 'Ukuran gambar sampul tidak boleh melebihi 2 MB. Silakan kompres foto Anda terlebih dahulu.',
                    confirmButtonColor: '#DC2626',
                });
                fileInput.value = '';
            }
        }
    });
</script>
@endsection