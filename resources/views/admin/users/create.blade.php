@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6 text-gray-800">Tambah Pengguna Baru</h2>
    
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Role / Hak Akses</label>
            <select name="role" class="w-full border rounded p-2 bg-white">
                <option value="misdinar">Misdinar</option>
                <option value="lektor">Lektor</option>
                <option value="direktur_musik">Direktur Musik</option>
                <option value="pengurus_gereja">Pengurus Gereja</option>
                <option value="admin">Admin (Super User)</option>
            </select>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Batal</a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan User</button>
        </div>
    </form>
</div>
@endsection