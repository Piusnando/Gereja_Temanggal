<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
// --- IMPORT BARU YANG DIPERLUKAN ---
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login dengan Rate Limiter Manual
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // --- STEP A: Cek Rate Limiter ---
        // Kunci unik berdasarkan Email + IP Address
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // Cek apakah sudah terlalu banyak mencoba (Max 5 kali)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // --- STEP B: Coba Login ---
        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();
            
            // Hapus hitungan gagal jika berhasil login
            RateLimiter::clear($throttleKey);

            return redirect()->intended(route('dashboard'));
        }

        // --- STEP C: Jika Gagal ---
        // Tambahkan hitungan gagal (blokir selama 60 detik jika sudah 5x)
        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}