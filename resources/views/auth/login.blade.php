<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Gereja St. Ignatius Loyola</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; overflow: hidden; }
        .font-catholic { font-family: 'Cinzel', serif; }
        [x-cloak] { display: none !important; }

        /* Animasi Cahaya Berdenyut */
        @keyframes holy-pulse {
            0% { transform: translate(-50%, -50%) scale(0.95); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.05); opacity: 0.8; }
            100% { transform: translate(-50%, -50%) scale(0.95); opacity: 0.5; }
        }
        .holy-glow-animation {
            animation: holy-pulse 3s infinite ease-in-out;
        }

        /* ANIMASI BARU: Loading Bar dari Kiri ke Kanan */
        @keyframes fill-bar {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        .animate-fill {
            /* Durasi 2.5 detik sesuai waktu tunggu redirect di JS */
            animation: fill-bar 2.5s ease-out forwards; 
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen relative" x-data="loginApp()">

    <!-- 1. EFEK CAHAYA BELAKANG (UKURAN PAS) -->
    <div x-show="state === 'success'"
         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
                w-[150vw] h-[150vw] md:w-[60vw] md:h-[60vw] 
                bg-gradient-to-r from-yellow-200 via-white to-yellow-200 
                rounded-full 
                blur-3xl 
                opacity-60 
                z-0 holy-glow-animation pointer-events-none"
         x-transition:enter="transition ease-out duration-1000"
         x-transition:enter-start="opacity-0 scale-50"
         x-transition:enter-end="opacity-60 scale-100">
    </div>

    <!-- 2. KONTEN UTAMA (KARTU LOGIN) -->
    <!-- Tambahkan 'relative z-10' agar kartu muncul DI ATAS cahaya -->
    <main class="relative z-10 w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 rounded-2xl shadow-2xl overflow-hidden bg-white transition-all duration-500"
          :class="state === 'success' ? 'shadow-yellow-500/50 scale-105' : 'shadow-2xl'">

        <!-- KOLOM KIRI: VIDEO ANIMASI -->
        <div class="relative bg-blue-900 flex items-center justify-center overflow-hidden h-64 md:h-auto">
            
            <!-- Video 1: St. Ignatius (Default) -->
            <!-- preload="auto" karena ini yang pertama dilihat -->
            <video x-show="state === 'idle'" x-ref="ignatiusVideo" class="absolute h-full w-full object-cover object-center" autoplay muted loop playsinline preload="auto" x-transition>
                <source src="{{ asset('videos/ignatius-sapa.mp4') }}" type="video/mp4">
            </video>
            
            <!-- Video 2: Yesus Memberkati (Sukses) -->
            <!-- TAMBAHKAN preload="none" -->
            <video x-show="state === 'success'" x-ref="successVideo" class="absolute h-full w-full object-cover object-center" muted playsinline preload="none" x-transition>
                <source src="{{ asset('videos/yesus-berkati.mp4') }}" type="video/mp4">
            </video>
            
            <!-- Video 3: Ignatius Berdoa (Gagal) -->
            <!-- TAMBAHKAN preload="none" -->
            <video x-show="state === 'error'" x-ref="errorVideo" class="absolute h-full w-full object-cover object-center" muted playsinline preload="none" x-transition>
                <source src="{{ asset('videos/ignatius-berdoa.mp4') }}" type="video/mp4">
            </video>
        </div>

        <!-- KOLOM KANAN: FORM -->
        <div class="p-6 md:p-12 flex flex-col justify-center">
            
            <!-- A. Form Login -->
            <div x-show="state === 'idle' || state === 'loading'" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
                
                <div class="mb-6 md:mb-8 text-center">
                    <p class="text-lg font-semibold text-gray-600 mb-1 md:mb-2">Selamat Datang di</p>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 font-catholic tracking-wider">ADMIN PANEL</h2>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">Gereja St. Ignatius Loyola, Temanggal</p>
                </div>

                <form @submit.prevent="submitLogin">
                    <div class="mb-4 md:mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" x-model="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                    </div>
                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <!-- Input dinamis berubah tipe text/password -->
                            <input :type="showPassword ? 'text' : 'password'" x-model="password" 
                                   class="w-full pl-4 pr-12 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                            
                            <!-- Tombol Mata (Toggle) -->
                            <button type="button" @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition focus:outline-none">
                                
                                <!-- Ikon Mata Terbuka (Saat password sembunyi) -->
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                
                                <!-- Ikon Mata Tertutup/Coret (Saat password terlihat) -->
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div x-show="errorMessage" x-transition class="mb-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm text-center">
                        <span x-text="errorMessage"></span>
                    </div>
                    <button type="submit" :disabled="isLoading" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition disabled:opacity-50 flex justify-center items-center">
                        <span x-show="!isLoading">MASUK</span>
                        <svg x-show="isLoading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </button>
                </form>
            </div>

            <!-- B. Tampilan Sukses (State: success) -->
            <div x-show="state === 'success'" x-cloak class="text-center py-8"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <h1 class="text-3xl md:text-4xl font-bold font-catholic text-green-600 mb-2">Berkah Dalem</h1>
                <p class="text-gray-600 text-sm">Login berhasil. Mengarahkan...</p>
                
                <!-- Loading Bar Animasi -->
                <div class="w-full bg-gray-200 rounded-full h-2 mt-6 overflow-hidden shadow-inner">
                    <!-- Class 'animate-fill' akan menjalankan animasi CSS yang kita buat tadi -->
                    <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full animate-fill"></div>
                </div>

            </div>

            <!-- C. Tampilan Gagal -->
            <div x-show="state === 'error'" x-cloak class="text-center py-8"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <h1 class="text-3xl md:text-4xl font-bold font-catholic text-red-600">Gagal</h1>
                <p class="text-gray-600 mt-2" x-text="errorMessage"></p>
                <p class="text-sm text-gray-500 mt-4">Silakan coba lagi.</p>
            </div>

        </div>
    </main>

    <!-- SCRIPT (Tetap sama) -->
    <script>
        // ... (Script JS Anda sebelumnya sudah benar, tidak perlu diubah) ...
         function loginApp() {
            return {
                email: '', password: '',showPassword: false, state: 'idle', isLoading: false, errorMessage: '',
                initPage() { this.$refs.ignatiusVideo.play().catch(e => console.error("Video error", e)); },
                async submitLogin() {
                    this.isLoading = true; this.errorMessage = '';
                    try {
                        const response = await fetch('{{ route("login.post") }}', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: JSON.stringify({ email: this.email, password: this.password })
                        });
                        const data = await response.json();
                        if (response.ok) {
                            this.state = 'success';
                            this.$refs.successVideo.play();
                            this.$refs.successVideo.onended = () => { window.location.href = data.redirect_url; };
                        } else {
                            this.isLoading = false; this.state = 'error';
                            this.errorMessage = data.message || 'Login gagal.';
                            this.$refs.errorVideo.play();
                            this.$refs.errorVideo.onended = () => { this.state = 'idle'; };
                        }
                    } catch (error) {
                        this.isLoading = false; this.state = 'idle'; this.errorMessage = 'Kesalahan jaringan.';
                    }
                }
            }
        }
    </script>
</body>
</html>