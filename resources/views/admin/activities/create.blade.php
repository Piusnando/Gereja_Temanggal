@extends('layouts.admin')

@section('content')
<!-- Load CSS Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-3">Buat Berita Kegiatan Baru</h2>
    
    <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Judul Berita / Nama Kegiatan</label>
            <input type="text" name="title" class="w-full border border-gray-300 rounded p-2.5" placeholder="Contoh: Rekoleksi OMK di Kaliurang" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Penyelenggara</label>
                <input type="text" name="organizer" class="w-full border border-gray-300 rounded p-2.5" placeholder="Contoh: OMK Kalasan" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Kegiatan</label>
                <input type="text" name="location" class="w-full border border-gray-300 rounded p-2.5" value="Gereja St. Ignatius Loyola" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Waktu Mulai</label>
                <input type="datetime-local" name="start_time" class="w-full border border-gray-300 rounded p-2.5" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Waktu Selesai (Opsional)</label>
                <input type="datetime-local" name="end_time" class="w-full border border-gray-300 rounded p-2.5">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Target Lingkungan</label>
            <select name="lingkungan_id" class="w-full border border-gray-300 rounded p-2.5 bg-white">
                <option value="">-- Semua Lingkungan (Kegiatan Gereja) --</option>
                @foreach($lingkungans as $ling)
                    <option value="{{ $ling->id }}">Lingkungan {{ $ling->name }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Pilih "Semua Lingkungan" jika ini acara umum gereja. Pilih nama lingkungan jika ini acara spesifik.</p>
        </div>

        <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-200">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="show_on_lingkungan_page" value="1" 
                       class="form-checkbox h-5 w-5 text-blue-600 rounded" checked>
                <span class="ml-3 text-gray-700 font-medium">
                    Tampilkan di Halaman Lingkungan?
                </span>
            </label>
            <p class="text-xs text-gray-500 mt-2 ml-8">
                Jika dicentang, kegiatan ini akan muncul di halaman detail Lingkungan yang dipilih (atau di semua halaman Lingkungan jika "Semua Lingkungan" dipilih). <br>
                Hilangkan centang jika ini berita/kegiatan yang hanya ingin tampil di halaman utama "Kegiatan".
            </p>
        </div>

        <!-- EDITOR SUMMERNOTE -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Lengkap & Foto Kegiatan</label>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-3 text-sm rounded mb-3">
                <p class="font-bold">Penting:</p>
                <p>Jika menyisipkan foto di dalam teks, pastikan ukurannya **kurang dari 2 MB** untuk menghindari error saat menyimpan.</p>
            </div>
            <textarea id="summernote" name="description" required></textarea>
        </div>

        <!-- GAMBAR SAMPUL -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Gambar Sampul (Thumbnail)</label>
            <!-- Beri ID pada input file -->
            <input id="imageInput" type="file" name="image" class="w-full border border-gray-300 rounded p-2 bg-gray-50 text-sm">
            <p class="text-xs text-gray-500 mt-1">Ukuran file maksimal **2 MB**.</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Simpan Berita</button>
        </div>
    </form>
</div>

<!-- JS JQuery & Summernote -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Library untuk Alert Cantik -->

<script>
    // Inisialisasi Summernote
    $('#summernote').summernote({
        placeholder: 'Tulis detail kegiatan...',
        tabsize: 2,
        height: 400
    });

    // Validasi Ukuran File Sebelum Submit
    document.getElementById('activityForm').addEventListener('submit', function(event) {
        const fileInput = document.getElementById('imageInput');
        
        // Cek jika ada file yang dipilih
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB

            if (file.size > maxSizeInBytes) {
                // 1. Batalkan proses submit form
                event.preventDefault();

                // 2. Tampilkan Alert Cantik
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Terlalu Besar!',
                    text: 'Ukuran gambar sampul tidak boleh melebihi 2 MB. Silakan kompres foto Anda terlebih dahulu.',
                    confirmButtonColor: '#DC2626', // Warna Merah
                });

                // 3. Kosongkan input file
                fileInput.value = '';
            }
        }
    });
</script>
@endsection