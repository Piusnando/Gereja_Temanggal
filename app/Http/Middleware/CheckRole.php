<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Jika user adalah 'admin', dia boleh masuk kemana saja (Bypass)
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Cek apakah role user ada di daftar role yang diizinkan route ini
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, lempar ke dashboard dengan pesan error
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}