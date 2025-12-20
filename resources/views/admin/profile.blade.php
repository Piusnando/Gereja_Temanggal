@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    
    <h1 class="text-3xl font-extrabold text-gray-800 mb-8 border-b pb-3">Pengaturan Akun Saya</h1>

    {{-- ALERTS (Sukses & Error) --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition 
             class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex justify-between items-center">
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-700 hover:text-green-900 font-bold text-xl ml-4">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition 
             class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md flex justify-between items-start">
            <div class="flex items-start">
                <svg class="h-6 w-6 mr-2 mt-0.5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-bold block mb-1">Terjadi Kesalahan:</span>
                    <ul class="list-disc list-inside text-sm leading-relaxed">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button @click="show = false" class="text-red-700 hover:text-red-900 font-bold text-xl ml-4">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- CARD 1: EDIT PROFIL -->
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-100">
            <h2 class="text-xl font-bold text-gray-700 mb-4 pb-3 border-b">Data Diri</h2>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Foto Profil Preview -->
                <div class="text-center">
                    <div class="relative w-28 h-28 mx-auto mb-3 rounded-full overflow-hidden border-4 border-gray-100 shadow-lg">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-600 flex items-center justify-center text-white text-4xl font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <label class="block">
                        <span class="sr-only">Pilih foto profil</span>
                        <input type="file" name="avatar" 
                               class="block w-full text-sm text-gray-500
                                 file:mr-4 file:py-2 file:px-4
                                 file:rounded-full file:border-0
                                 file:text-sm file:font-semibold
                                 file:bg-blue-50 file:text-blue-700
                                 hover:file:bg-blue-100 transition-all duration-200"/>
                    </label>
                    @error('avatar') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('name') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                           class="w-full border border-gray-300 rounded-lg p-3 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Simpan Perubahan Data Diri
                </button>
            </form>
        </div>

        <!-- CARD 2: GANTI PASSWORD -->
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-100 h-fit">
            <h2 class="text-xl font-bold text-gray-700 mb-4 pb-3 border-b">Ganti Password</h2>
            
            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password Lama</label>
                    <input type="password" name="current_password" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan password lama" required>
                    @error('current_password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 8 karakter" required>
                    @error('password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                    Update Password
                </button>
            </form>

            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm rounded-lg">
                <p class="font-semibold mb-1">Tips Keamanan:</p>
                <p>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk password yang kuat.</p>
            </div>
        </div>

    </div>
</div>
@endsection