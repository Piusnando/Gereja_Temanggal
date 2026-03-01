<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login dengan AJAX & Rate Limiting
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // A. Kunci Unik untuk Rate Limiter
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // B. Cek apakah sudah terlalu banyak mencoba
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $message = 'Terlalu banyak percobaan. Silakan coba lagi dalam ' . $seconds . ' detik.';

            // Jika request minta JSON (dari AJAX), kirim respon JSON
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $message], 429);
            }
            
            // Fallback untuk non-AJAX
            throw ValidationException::withMessages(['email' => $message]);
        }

        // C. Coba Lakukan Login
        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey); // Hapus catatan gagal

            // Jika request minta JSON, kirim respon SUKSES dengan URL redirect
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'redirect_url' => route('dashboard')
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        // D. Jika Login Gagal
        RateLimiter::hit($throttleKey, 60); // Tambah hitungan gagal

        // Jika request minta JSON, kirim respon GAGAL
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.'
            ], 422); // 422 Unprocessable Entity
        }

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