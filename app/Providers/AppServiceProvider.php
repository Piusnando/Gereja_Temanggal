<?php

namespace App\Providers;

use App\Models\Territory;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL; // <--- 1. WAJIB DITAMBAHKAN

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // --- 2. LOGIKA FIX NGROK (HTTPS) ---
        // Jika request datang dari Ngrok (biasanya membawa header X-Forwarded-Proto: https)
        // Kita paksa Laravel membuat semua link menjadi HTTPS.
        // if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
        //     URL::forceScheme('https');
        // }
        // -----------------------------------

        // Cek dulu apakah tabel database sudah ada (untuk menghindari error saat migrate awal)
        if (Schema::hasTable('site_settings')) {
            $logoSetting = SiteSetting::where('key', 'site_logo')->first();
            
            // Tentukan URL Logo
            $globalLogo = $logoSetting && $logoSetting->value 
                ? asset('storage/' . $logoSetting->value) 
                : 'https://via.placeholder.com/150?text=Logo'; 

            // Bagikan variabel $globalLogo ke semua view
            View::share('globalLogo', $globalLogo);

            // Gunakan Tailwind untuk Pagination
            Paginator::useTailwind();
        }

        // Share data Wilayah ke semua view untuk Navbar Dropdown
        if (Schema::hasTable('territories')) {
            $globalTerritories = Territory::all();
            View::share('globalTerritories', $globalTerritories);
        }
    }
}