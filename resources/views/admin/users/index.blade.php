@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Tambah User Baru
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
            <tr>
                <th class="px-5 py-3 text-left">Nama</th>
                <th class="px-5 py-3 text-left">Email</th>
                <th class="px-5 py-3 text-left">Role</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 text-sm">
            @foreach($users as $user)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-5 py-4 flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 text-xs">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <span class="font-bold">{{ $user->name }}</span>
                </td>
                <td class="px-5 py-4">{{ $user->email }}</td>
                <td class="px-5 py-4">
                    <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                        {{ $user->role == 'admin' ? 'bg-red-100 text-red-700' : 
                          ($user->role == 'pengurus_gereja' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 font-bold">Edit</a>
                        @if(auth()->id() != $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900 font-bold ml-2">Hapus</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection