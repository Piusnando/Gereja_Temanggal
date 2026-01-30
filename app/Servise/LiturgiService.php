<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class LiturgiService
{
    /**
     * Mengambil data liturgi hari ini.
     * Data di-cache selama 24 jam agar tidak membebani server imankatolik.
     */
    public function getLiturgiHariIni()
    {
        return Cache::remember('liturgi_hari_ini', 60 * 24, function () {
            try {
                // 1. Kunjungi Website
                $response = Http::get('https://www.imankatolik.or.id/');
                $html = $response->body();

                // 2. Baca HTML
                $crawler = new Crawler($html);

                // 3. Cari Elemen (Ini harus disesuaikan dengan struktur HTML imankatolik saat ini)
                // Biasanya ada di dalam div tertentu. Ini contoh logika pencariannya:
                
                $data = [
                    'tanggal' => now()->locale('id')->translatedFormat('l, d F Y'),
                    'warna' => 'Hijau', // Default
                    'bacaan_1' => '-',
                    'mazmur' => '-',
                    'injil' => '-',
                    'perayaan' => 'Hari Biasa',
                ];

                // *Contoh Logika Scraping (Perlu disesuaikan jika web sumber update)*
                // Cari teks warna liturgi (biasanya ada di teks pojok kanan atau css class)
                $warnaNode = $crawler->filter('div.kiri_atas'); 
                if ($warnaNode->count() > 0) {
                    $text = $warnaNode->text();
                    if (stripos($text, 'Putih') !== false) $data['warna'] = 'Putih';
                    elseif (stripos($text, 'Merah') !== false) $data['warna'] = 'Merah';
                    elseif (stripos($text, 'Ungu') !== false) $data['warna'] = 'Ungu';
                    elseif (stripos($text, 'Hijau') !== false) $data['warna'] = 'Hijau';
                }

                // Cari Bacaan (Biasanya ada link ke alkitab)
                // Ini teknik kasar: ambil semua link yang mengarah ke alkitab sabda
                $links = $crawler->filter('a[href*="alkitab.sabda.org"]')->each(function (Crawler $node) {
                    return $node->text();
                });

                if (!empty($links)) {
                    $data['bacaan_1'] = $links[0] ?? '-';
                    $data['mazmur'] = $links[1] ?? '-';
                    $data['injil'] = end($links) ?? '-';
                }

                // Cari Nama Perayaan (Biasanya heading h2 atau h3)
                $perayaan = $crawler->filter('td.content_tengah h3');
                if($perayaan->count() > 0) {
                    $data['perayaan'] = $perayaan->text();
                }

                return $data;

            } catch (\Exception $e) {
                // Jika error/web sumber down, kembalikan data default/kosong
                return null;
            }
        });
    }
}