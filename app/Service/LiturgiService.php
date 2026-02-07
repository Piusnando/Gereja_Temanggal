<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;

class LiturgiService
{
    public function getLiturgiHariIni()
    {
        // 1. Definisikan tanggal DI LUAR Cache
        $now = Carbon::now('Asia/Jakarta');
        
        // 2. Buat Kunci Cache Unik berdasarkan Tanggal (misal: liturgi_2026-02-07)
        // Ini menjamin cache otomatis "basi" saat tanggal berganti
        $cacheKey = 'liturgi_harian_' . $now->format('Y-m-d');

        // 3. PENTING: Tambahkan 'use ($now)' agar variabel bisa dibaca di dalam fungsi
        return Cache::remember($cacheKey, 60 * 24, function () use ($now) {
            
            $liturgiData = [
                'tanggal' => $now->locale('id')->translatedFormat('l, d F Y'),
                'perayaan' => 'Hari Biasa',
                'warna' => 'Hijau',
                'bacaan_1' => '-',
                'mazmur' => '-',
                'injil' => '-',
            ];

            // --- A. API WARNA ---
            try {
                $apiUrl = "https://calapi.inadiutorium.cz/api/v0/id/calendars/general-roman/" . $now->format('Y/m/d');
                $responseApi = Http::timeout(3)->get($apiUrl);
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
            } catch (\Exception $e) { }

            // --- B. SCRAPING BACAAN (PKARM CSE - SMART FILTER) ---
            try {
                $client = new Client();
                $dateCode = $now->format('Ymd');
                $urlTarget = "https://www.renunganpkarmcse.com/m.php?p=p" . $dateCode;

                // Logika Fallback Tahun (Untuk Localhost 2026 -> ambil data 2025)
                try {
                    $response = $client->request('GET', $urlTarget, [
                        'headers' => ['User-Agent' => 'Mozilla/5.0'],
                        'verify' => false, 'timeout' => 10,
                    ]);
                } catch (\Exception $e) {
                    $realYear = date('Y'); // Tahun server asli
                    if ($now->year != $realYear) {
                        $fallbackDate = $now->copy()->year($realYear)->format('Ymd');
                        $urlTarget = "https://www.renunganpkarmcse.com/m.php?p=p" . $fallbackDate;
                        $response = $client->request('GET', $urlTarget, ['verify' => false]);
                    } else { throw $e; }
                }

                if ($response->getStatusCode() == 200) {
                    $html = (string) $response->getBody();
                    $plainText = strip_tags($html); 
                    
                    // Regex cari ayat
                    preg_match_all('/([1-3]?\s*[A-Z][a-z]{2,5}\.?\s*\d+\s*:\s*[\d,\-]+(?:[a-z])?)/', $plainText, $matches);
                    
                    $candidates = array_unique($matches[0] ?? []);
                    $cleanLinks = [];

                    foreach ($candidates as $txt) {
                        $txt = trim($txt);
                        if (strlen($txt) > 4 && preg_match('/\d/', $txt)) {
                            $cleanLinks[] = $txt;
                        }
                    }
                    
                    // Smart Mapping
                    $injilCandidates = [];
                    $mazmurCandidates = [];
                    $otherCandidates = [];

                    foreach ($cleanLinks as $link) {
                        if (preg_match('/^(Mat|Mrk|Luk|Yoh)/i', $link)) {
                            $injilCandidates[] = $link;
                        } elseif (stripos($link, 'Mzm') !== false || stripos($link, 'Mazmur') !== false) {
                            $mazmurCandidates[] = $link;
                        } else {
                            $otherCandidates[] = $link;
                        }
                    }

                    if (!empty($injilCandidates)) {
                        $liturgiData['injil'] = end($injilCandidates);
                    }
                    if (!empty($mazmurCandidates)) {
                        $liturgiData['mazmur'] = $mazmurCandidates[0];
                    }
                    if (!empty($otherCandidates)) {
                        $liturgiData['bacaan_1'] = $otherCandidates[0];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Scraping Error: ' . $e->getMessage());
            }

            return $liturgiData;
        });
    }
}