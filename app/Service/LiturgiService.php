<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use GuzzleHttp\Client; // Import Guzzle
use GuzzleHttp\Exception\RequestException;

class LiturgiService
{
    public function getLiturgiHariIni()
    {
        $now = Carbon::now('Asia/Jakarta');
        
        // Cache Key V17 (Wajib ganti)
        return Cache::remember('liturgi_data_v17_' . $now->format('Y-m-d'), 60 * 12, function () use ($now) {
            
            // --- DATA DEFAULT ---
            $liturgiData = [
                'tanggal' => $now->locale('id')->translatedFormat('l, d F Y'),
                'perayaan' => 'Hari Biasa',
                'warna' => 'Hijau',
                'bacaan_1' => '-',
                'mazmur' => '-',
                'injil' => '-',
            ];

            // --- API WARNA (Tetap pakai Laravel HTTP) ---
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

            // --- SCRAPING BACAAN (MENGGUNAKAN GUZZLE) ---
            try {
                // Manipulasi Tahun
                $scrapeYear = $now->year;
                if ($scrapeYear > Carbon::now()->year) {
                    $scrapeYear = Carbon::now()->year; 
                }
                $urlTarget = "https://www.imankatolik.or.id/kalender.php?d=" . $now->day . "&m=" . $now->month . "&y=" . $scrapeYear;
                
                // Buat Guzzle Client
                $client = new Client();
                
                // Kirim Request dengan Opsi Lengkap
                $response = $client->request('GET', $urlTarget, [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                        'Accept-Language' => 'en-US,en;q=0.9',
                    ],
                    'verify' => false, // Abaikan SSL
                    'timeout' => 15, // Waktu tunggu
                    'connect_timeout' => 10, // Waktu tunggu koneksi
                ]);

                if ($response->getStatusCode() == 200) {
                    $html = (string) $response->getBody();
                    
                    if (strlen($html) < 500) {
                        $liturgiData['bacaan_1'] = "HTML Kosong";
                        return $liturgiData;
                    }

                    $plainText = strip_tags($html);
                    preg_match_all('/([1-3]?[A-Za-z]{2,5}\.?\s*\d+\s*:\s*\d+(?:[-\d,a-z]*))/i', $plainText, $matches);
                    
                    $candidates = array_unique($matches[0] ?? []);
                    $cleanLinks = [];
                    foreach ($candidates as $txt) {
                        $txt = trim($txt);
                        if (preg_match('/^[A-Za-z]/', $txt) && strlen($txt) > 4 && !preg_match('/^\d+:\d+$/', $txt)) {
                            $cleanLinks[] = $txt;
                        }
                    }
                    $cleanLinks = array_values($cleanLinks);

                    if (!empty($cleanLinks)) {
                        $liturgiData['bacaan_1'] = $cleanLinks[0];
                        // ... (logika mazmur & injil tetap sama) ...
                        $mazmurFound = false; $injilFound = false;
                        foreach ($cleanLinks as $link) {
                            if (stripos($link, 'Mzm') !== false || stripos($link, 'Mazmur') !== false) { $liturgiData['mazmur'] = $link; $mazmurFound = true; }
                            if (preg_match('/(Mat|Mrk|Luk|Yoh)/i', $link) && $link !== $cleanLinks[0]) { $liturgiData['injil'] = $link; $injilFound = true; }
                        }
                        if (!$mazmurFound && count($cleanLinks) >= 3) $liturgiData['mazmur'] = $cleanLinks[1];
                        if (!$injilFound) $liturgiData['injil'] = end($cleanLinks);
                    } else {
                        $liturgiData['bacaan_1'] = "Data Kosong";
                    }
                }
            } catch (RequestException $e) {
                // Tangkap error spesifik Guzzle
                Log::error('Guzzle Error: ' . $e->getMessage());
                if ($e->hasResponse()) {
                    Log::error('Guzzle Status: ' . $e->getResponse()->getStatusCode());
                }
                $liturgiData['bacaan_1'] = "Error Koneksi (Guzzle)";
            } catch (\Exception $e) {
                // Tangkap error umum lainnya
                Log::error('General Scraping Error: ' . $e->getMessage());
                $liturgiData['bacaan_1'] = "Error Umum";
            }

            return $liturgiData;
        });
    }
}