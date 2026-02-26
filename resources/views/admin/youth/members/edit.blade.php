@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-100">
    <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Anggota {{ $member->category }}</h2>
    
    <form action="{{ route('admin.youth.members.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ $member->name }}" class="w-full border border-gray-300 rounded p-2.5 focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Lingkungan</label>
            <select name="lingkungan_id" class="w-full border border-gray-300 rounded p-2.5 bg-white focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Lingkungan --</option>
                @foreach($lingkungans as $l)
                    <option value="{{ $l->id }}" {{ $member->lingkungan_id == $l->id ? 'selected' : '' }}>
                        Lingkungan {{ $l->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ $member->birth_date }}" class="w-full border border-gray-300 rounded p-2.5">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">No. HP (WA)</label>
                <input type="text" name="phone" value="{{ $member->phone }}" class="w-full border border-gray-300 rounded p-2.5">
            </div>
        </div>

        <div class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" class="form-checkbox h-5 w-5 text-blue-600 rounded" {{ $member->is_active ? 'checked' : '' }}>
                <span class="ml-2 text-gray-800 font-bold">Status Aktif?</span>
            </label>
            <p class="text-xs text-gray-500 ml-7 mt-1">
                Jika tidak dicentang, anggota ini tidak akan dihitung dalam statistik aktif.
            </p>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.youth.members.index', ['category' => $member->category]) }}" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">Update</button>
        </div>
    </form>
</div>
@endsection