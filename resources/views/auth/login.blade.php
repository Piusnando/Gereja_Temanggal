<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Gereja St. Ignatius Loyola</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js (Wajib untuk fitur show/hide password) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden border-t-4 border-blue-800">
        
        <!-- Header Login -->
        <div class="bg-blue-50 py-6 text-center px-4">
            <h2 class="text-2xl font-bold text-blue-900">Admin Panel</h2>
            <p class="text-sm text-gray-600 mt-1">Gereja St. Ignatius Loyola Temanggal</p>
        </div>

        <div class="p-8">
            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                    <strong class="font-bold">Login Gagal!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                            placeholder="admin@gereja.com">
                    </div>
                </div>

                <!-- Password Input (DENGAN FITUR SHOW/HIDE) -->
                <!-- x-data mengontrol status tampil/sembunyi -->
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <!-- Icon Gembok di Kiri -->
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        
                        <!-- Input Password (Type berubah dinamis) -->
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="pl-10 pr-10 w-full border border-gray-300 rounded-lg py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                            placeholder="********">

                        <!-- Tombol Mata di Kanan -->
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            
                            <!-- Icon Mata Coret (Sembunyi) - Muncul saat show == false -->
                            <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>

                            <!-- Icon Mata (Tampil) - Muncul saat show == true -->
                            <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Submit -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="ml-2">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition duration-150 shadow-md">
                    Masuk
                </button>
            </form>
        </div>
        
        <div class="bg-gray-50 px-4 py-4 border-t border-gray-200 text-center">
            <a href="/" class="text-sm text-gray-500 hover:text-blue-800 font-medium transition">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>