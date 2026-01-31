<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LiturgiService
{
    public function getLiturgiHariIni()
    {
        // Ganti versi cache ke 'v7' (PENTING!)
        return Cache::remember('liturgi_hari_ini_hybrid_v7', 60 * 24, function () {
            
            // 1. DATA DEFAULT
            $liturgiData = [
                'tanggal' => Carbon::now()->locale('id')->translatedFormat('l, d F Y'),
                'perayaan' => 'Hari Biasa',
                'warna' => 'Hijau',
                'bacaan_1' => '-',
                'mazmur' => '-',
                'injil' => '-',
            ];

            // 2. API WARNA (CalAPI)
            try {
                $responseApi = Http::timeout(5)->get('https://calapi.inadiutorium.cz/api/v0/id/calendars/general-roman/today');
                if ($responseApi->successful()) {
                    $apiData = $responseApi->json();
                    $celebration = $apiData['celebrations'][0] ?? null;
                    if ($celebration) {
                        $liturgiData['perayaan'] = $celebration['title'];
                        $liturgiData['warna'] = match (strtolower($celebration['colour'] ?? '')) {
                            'white' => 'Putih', 'red' => 'Merah', 'green' => 'Hijau', 'violet' => 'Ungu', 'rose' => 'Merah Muda', default => 'Hijau',
                        };
                    }
                }
            } catch (\Exception $e) {
                // Silent fail untuk API
            }

            // 3. SCRAPING BACAAN (METODE TEKS MURNI)
            try {
                // Ambil halaman kalender karena lebih bersih isinya
                $responseScraper = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36',
                ])
                ->withoutVerifying()
                ->get('https://www.imankatolik.or.id/kalender.php');

                if ($responseScraper->successful()) {
                    // LANGKAH PENTING: Hapus semua tag HTML, sisakan teks saja
                    $plainText = strip_tags($responseScraper->body());
                    
                    // Regex yang sangat fleksibel:
                    // Mencari [Huruf][Titik/Spasi][Angka][Titik Dua][Angka]
                    // Contoh yang ditangkap: "Kej 1:1", "Mat. 5:3", "Mzm 23:1-5"
                    preg_match_all('/([1-3]?[A-Za-z]{2,5}\.?\s*\d+\s*:\s*\d+(?:[-\d,a-z]*))/i', $plainText, $matches);

                    // Ambil hasil yang unik dan bersihkan
                    $candidates = array_unique($matches[0] ?? []);
                    $cleanLinks = [];

                    // Filter manual untuk membuang yang bukan kitab suci (misal jam "18:00")
                    foreach ($candidates as $txt) {
                        $txt = trim($txt);
                        // Pastikan diawali huruf (bukan jam) dan panjangnya masuk akal
                        if (preg_match('/^[A-Za-z]/', $txt) && strlen($txt) > 4) {
                            $cleanLinks[] = $txt;
                        }
                    }

                    // Reset index array
                    $cleanLinks = array_values($cleanLinks);

                    if (!empty($cleanLinks)) {
                        // Logika Penempatan
                        $liturgiData['bacaan_1'] = $cleanLinks[0]; // Biasanya urutan pertama di teks

                        // Cari yang mengandung "Mzm" untuk Mazmur
                        $mazmurFound = false;
                        foreach ($cleanLinks as $link) {
                            if (stripos($link, 'Mzm') !== false || stripos($link, 'Mazmur') !== false) {
                                $liturgiData['mazmur'] = $link;
                                $mazmurFound = true;
                                break;
                            }
                        }
                        // Jika tidak ada label "Mzm", asumsikan urutan kedua adalah Mazmur (jika ada 3 bacaan)
                        if (!$mazmurFound && count($cleanLinks) >= 3) {
                            $liturgiData['mazmur'] = $cleanLinks[1];
                        }

                        // Cari Injil (Mat, Mrk, Luk, Yoh)
                        $injilFound = false;
                        foreach ($cleanLinks as $link) {
                            if (preg_match('/(Mat|Mrk|Luk|Yoh)/i', $link)) {
                                // Pastikan bukan bacaan 1 (kadang bacaan 1 dari injil surat)
                                if ($link !== $liturgiData['bacaan_1']) {
                                    $liturgiData['injil'] = $link;
                                    $injilFound = true;
                                }
                            }
                        }
                        // Fallback: Ambil yang terakhir
                        if (!$injilFound) {
                            $liturgiData['injil'] = end($cleanLinks);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Scraping Error: ' . $e->getMessage());
            }

            return $liturgiData;
        });
    }
}