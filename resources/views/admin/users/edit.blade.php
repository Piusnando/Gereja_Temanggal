@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6 text-gray-800">Edit Data Pengguna</h2>
    
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded p-2" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded p-2" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru (Opsional)</label>
            <input type="password" name="password" class="w-full border rounded p-2" placeholder="Kosongkan jika tidak ingin mengganti">
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Role / Hak Akses</label>
            <select name="role" class="w-full border rounded p-2 bg-white">
                @foreach(['misdinar', 'lektor', 'direktur_musik', 'pengurus_gereja', 'admin'] as $roleOption)
                    <option value="{{ $roleOption }}" {{ $user->role == $roleOption ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $roleOption)) }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Batal</a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update User</button>
        </div>
    </form>
</div>
@endsection