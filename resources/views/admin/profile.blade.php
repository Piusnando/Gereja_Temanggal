@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Akun</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- CARD 1: EDIT PROFIL -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Data Diri</h2>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Foto Profil Preview -->
                <div class="mb-4 text-center">
                    <div class="relative w-24 h-24 mx-auto mb-3">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover border-4 border-gray-100 shadow-sm">
                        @else
                            <div class="w-full h-full rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-gray-100 shadow-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <label class="block">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file" name="avatar" class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-xs file:font-semibold
                          file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100
                        "/>
                    </label>
                    @error('avatar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full border rounded p-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- CARD 2: GANTI PASSWORD -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 h-fit">
            <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Ganti Password</h2>
            
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password Lama</label>
                    <input type="password" name="current_password" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="********" required>
                    @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 8 karakter" required>
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 rounded transition">
                    Update Password
                </button>
            </form>

            <div class="mt-4 p-3 bg-yellow-50 text-yellow-700 text-xs rounded border border-yellow-200">
                <p><strong>Catatan:</strong></p>
                <p>Gunakan password yang kuat (kombinasi huruf besar, kecil, dan angka) untuk keamanan akun pengurus.</p>
            </div>
        </div>

    </div>
</div>
@endsection