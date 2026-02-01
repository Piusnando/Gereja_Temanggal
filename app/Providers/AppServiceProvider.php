<?php

namespace App\Providers;

use App\Models\Territory;
use App\Models\SiteSetting;
use App\Models\OrganizationMember;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
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

        // if (Schema::hasTable('organization_members')) {
        
        // Menjadi baris 'if' yang baru ini (ada tambahan && Schema::hasColumn):
        if (Schema::hasTable('organization_members') && Schema::hasColumn('organization_members', 'bidang')) {
        
            // 1. Definisikan Bidang Utama
            $defaultBidang = [
                'Pengurus Harian',
                'Tim Pelayanan Bidang Liturgi',
                'Tim Pelayanan Bidang Sarana dan Prasarana',
                'Tim Pelayanan Bidang Umum',
                'Tim Pelayanan Bidang Pewartaan dan Pelayanan'
            ];

            // 2. Ambil data Bidang beserta Sub Bidangnya
            $query = OrganizationMember::select('bidang', 'sub_bidang')
                    ->whereNotNull('bidang')
                    ->where('bidang', '!=', '')
                    ->whereNotNull('sub_bidang');

        // CEK DULU: Apakah kolom 'tampil_di_menu' sudah ada di database?
        // Jika ada, baru kita filter. Jika belum (saat migrasi), lewati filter ini.
        if (Schema::hasColumn('organization_members', 'tampil_di_menu')) {
            $query->where('tampil_di_menu', true);
        }

        // Eksekusi Query
        $dbData = $query->distinct()->get();

            // 3. Susun Array Menu
            $organizationMenu = [];

            foreach ($defaultBidang as $b) {
                $organizationMenu[$b] = [];
            }

            foreach ($dbData as $item) {
                if ($item->bidang === 'Pengurus Harian') {
                    continue; 
                }
                $organizationMenu[$item->bidang][] = $item->sub_bidang;
            }

            // 4. Urutkan Sub-bidang
            foreach ($organizationMenu as $bidang => $subs) {
                sort($organizationMenu[$bidang]);
            }
            
            View::share('organizationMenu', $organizationMenu);
        }

    }
}