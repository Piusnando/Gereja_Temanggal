<?php

namespace App\Providers;

use App\Models\Territory;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting; // Tambahkan ini
use Illuminate\Support\Facades\View; // Tambahkan ini
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

            // Gunakan Tailwind untuk Pagination
            Paginator::useTailwind();
        }

        // Share data Wilayah ke semua view untuk Navbar Dropdown
        if (\Illuminate\Support\Facades\Schema::hasTable('territories')) {
            $globalTerritories = Territory::all();
            View::share('globalTerritories', $globalTerritories);
        }
    }
}