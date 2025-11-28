<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan ini
use App\Models\SiteSetting; // Tambahkan ini
use Illuminate\Support\Facades\Schema; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Agar logo tersedia di SEMUA file blade (main, home, admin, dll)
        
        // Cek dulu apakah tabel database sudah ada (untuk menghindari error saat migrate awal)
        if (Schema::hasTable('site_settings')) {
            $logoSetting = SiteSetting::where('key', 'site_logo')->first();
            
            // Tentukan URL Logo: Jika ada di DB pakai itu, jika tidak pakai placeholder
            $globalLogo = $logoSetting && $logoSetting->value 
                ? asset('storage/' . $logoSetting->value) 
                : 'https://via.placeholder.com/150?text=Logo'; // Gambar default jika kosong

            // Bagikan variabel $globalLogo ke semua view
            View::share('globalLogo', $globalLogo);
        }
    }
}