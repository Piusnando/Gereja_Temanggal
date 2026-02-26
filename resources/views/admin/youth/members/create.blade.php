@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah Anggota {{ $category }}</h2>
    
    <form action="{{ route('admin.youth.members.store') }}" method="POST">
        @csrf
        
        <!-- Hidden Input: Kategori otomatis terisi sesuai halaman -->
        <input type="hidden" name="category" value="{{ $category }}">

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded p-2.5 focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan nama lengkap">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Lingkungan</label>
            <select name="lingkungan_id" class="w-full border border-gray-300 rounded p-2.5 bg-white focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Lingkungan --</option>
                @foreach($lingkungans as $l)
                    <option value="{{ $l->id }}">Lingkungan {{ $l->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="w-full border border-gray-300 rounded p-2.5 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">No. HP / WA</label>
                <input type="text" name="phone" class="w-full border border-gray-300 rounded p-2.5 focus:ring-2 focus:ring-blue-500" placeholder="08xxxxx">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">Simpan</button>
        </div>
    </form>
</div>
@endsection