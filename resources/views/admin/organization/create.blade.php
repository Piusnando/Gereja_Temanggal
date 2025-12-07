@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6 text-gray-800">Tambah Anggota Organisasi</h2>
    
    <form action="{{ route('admin.organization.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- NAMA -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border rounded p-2 focus:ring-2 focus:ring-pink-500 focus:outline-none" required>
        </div>

        <!-- JABATAN -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan</label>
            <input type="text" name="position" class="w-full border rounded p-2 focus:ring-2 focus:ring-pink-500 focus:outline-none" placeholder="Contoh: Ketua, Anggota, Sekretaris" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            
            <!-- KATEGORI (ERRORNYA DISINI TADI) -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori Organisasi</label>
                <select name="category" class="w-full border rounded p-2 bg-white focus:ring-2 focus:ring-pink-500 focus:outline-none" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    
                    @foreach($allowed as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach

                </select>
                <p class="text-xs text-gray-500 mt-1">Sesuai hak akses Anda.</p>
            </div>

            <!-- LINGKUNGAN -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Lingkungan (Opsional)</label>
                <select name="lingkungan_id" class="w-full border rounded p-2 bg-white focus:ring-2 focus:ring-pink-500 focus:outline-none">
                    <option value="">-- Tidak Ada / Lintas Lingkungan --</option>
                    @foreach($lingkungans as $ling)
                        <option value="{{ $ling->id }}">{{ $ling->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- FOTO -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Foto Profil (Opsional)</label>
            <input type="file" name="image" class="w-full border rounded p-2 bg-gray-50 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
        </div>

        <!-- TOMBOL -->
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.organization.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded font-bold">Batal</a>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-6 rounded shadow transition">Simpan Anggota</button>
        </div>
    </form>
</div>
@endsection