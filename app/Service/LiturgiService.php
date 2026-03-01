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
        // Cache Key V37 (Translation Fix)
        return Cache::remember('liturgi_hari_ini_hybrid_v37_indo', 60 * 24, function () {
            
            $now = Carbon::now('Asia/Jakarta');

            $liturgiData = [
                'tanggal' => $now->locale('id')->translatedFormat('l, d F Y'),
                'perayaan' => 'Hari Biasa', // Default
                'perayaan_raw' => 'Feria',  // Untuk cek logic di view
                'warna' => 'Hijau',
                'bacaan_1' => '-',
                'mazmur' => '-',
                'injil' => '-',
            ];

            // 1. API WARNA & NAMA PERAYAAN (CalAPI)
            try {
                $apiUrl = "http://calapi.inadiutorium.cz/api/v0/en/calendars/default/" . $now->format('Y/m/d');
                $responseApi = Http::timeout(5)->get($apiUrl);

                if ($responseApi->successful()) {
                    $apiData = $responseApi->json();
                    $celebration = $apiData['celebrations'][0] ?? null;

                    if ($celebration) {
                        // Simpan raw title untuk keperluan debugging/logic
                        $liturgiData['perayaan_raw'] = $celebration['title'];
                        
                        // TERJEMAHKAN KE INDONESIA
                        $liturgiData['perayaan'] = $this->translateToIndonesian($celebration['title']);

                        $liturgiData['warna'] = match (strtolower($celebration['colour'] ?? '')) {
                            'white'  => 'Putih',
                            'red'    => 'Merah',
                            'green'  => 'Hijau',
                            'violet' => 'Ungu',
                            'rose'   => 'Merah Muda',
                            'black'  => 'Hitam',
                            default  => 'Hijau',
                        };
                    }
                }
            } catch (\Exception $e) {
                Log::error('CalAPI Error: ' . $e->getMessage());
            }

            // 2. SCRAPING BACAAN (PKARM CSE)
            try {
                $client = new Client();
                $dateCode = $now->format('Ymd');
                $urlTarget = "https://www.renunganpkarmcse.com/m.php?p=p" . $dateCode;

                try {
                    $response = $client->request('GET', $urlTarget, ['headers' => ['User-Agent' => 'Mozilla/5.0'], 'verify' => false, 'timeout' => 10]);
                } catch (\Exception $e) {
                    $realYear = date('Y');
                    if ($now->year != $realYear) {
                        $fallbackDate = $now->copy()->year($realYear)->format('Ymd');
                        $urlTarget = "https://www.renunganpkarmcse.com/m.php?p=p" . $fallbackDate;
                        $response = $client->request('GET', $urlTarget, ['verify' => false]);
                    } else { throw $e; }
                }

                if ($response->getStatusCode() == 200) {
                    $html = (string) $response->getBody();
                    $plainText = strip_tags($html); 
                    preg_match_all('/([1-3]?\s*[A-Z][a-z]{2,5}\.?\s*\d+\s*:\s*[\d,\-]+(?:[a-z])?)/', $plainText, $matches);
                    
                    $candidates = array_unique($matches[0] ?? []);
                    $cleanLinks = [];
                    foreach ($candidates as $txt) {
                        $txt = trim($txt);
                        if (strlen($txt) > 4 && preg_match('/\d/', $txt)) {
                            $cleanLinks[] = $txt;
                        }
                    }
                    
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

                    if (!empty($injilCandidates)) $liturgiData['injil'] = end($injilCandidates);
                    if (!empty($mazmurCandidates)) $liturgiData['mazmur'] = $mazmurCandidates[0];
                    if (!empty($otherCandidates)) $liturgiData['bacaan_1'] = $otherCandidates[0];
                }
            } catch (\Exception $e) {
                Log::error('Scraping Error: ' . $e->getMessage());
            }

            return $liturgiData;
        });
    }

    /**
     * Helper untuk menerjemahkan istilah Liturgi Inggris ke Indonesia
     */
    private function translateToIndonesian($text)
    {
        // 1. Kamus Kata
        $dictionary = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Lent' => 'Prapaskah',
            'Advent' => 'Adven',
            'Easter' => 'Paskah',
            'Ordinary Time' => 'Biasa',
            'Christmas' => 'Natal',
            'Saint' => 'Santo', // Akan dihandle khusus
            'St.' => 'St.',
            'Virgin' => 'Perawan',
            'Martyr' => 'Martir',
            'Bishop' => 'Uskup',
            'Priest' => 'Imam',
            'Doctor' => 'Pujangga Gereja',
            'Apostle' => 'Rasul',
            'Evangelist' => 'Pengarang Injil',
            'Feast' => 'Pesta',
            'Solemnity' => 'Hari Raya',
            'Memorial' => 'Peringatan',
            'Presentation of the Lord' => 'Pesta Yesus Dipersembahkan di Bait Allah',
            'Ash Wednesday' => 'Rabu Abu',
            'Palm Sunday' => 'Minggu Palma',
            'Good Friday' => 'Jumat Agung',
            'Holy Saturday' => 'Sabtu Suci',
            'Pentecost' => 'Pentakosta',
            'Feria' => 'Hari Biasa',
        ];

        // 2. Bersihkan Angka Urutan (1st, 2nd -> 1, 2)
        $text = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $text);

        // 3. Logic Khusus: "2 Sunday of Lent" -> "Minggu Prapaskah II"
        if (preg_match('/(\d+) Sunday of (Lent|Advent|Easter)/', $text, $matches)) {
            $mingguKe = $this->toRoman($matches[1]);
            $masa = $dictionary[$matches[2]] ?? $matches[2];
            return "Minggu $masa $mingguKe";
        }

        // 4. Logic Khusus: "Sunday of Ordinary Time" -> "Minggu Biasa XX"
        if (str_contains($text, 'Sunday in Ordinary Time')) {
            preg_match('/(\d+)/', $text, $matches);
            $angka = $matches[1] ?? '';
            return "Minggu Biasa " . $this->toRoman($angka);
        }

        // 5. Terjemahkan kata per kata dari kamus
        foreach ($dictionary as $en => $id) {
            // Ganti "Saint" jadi "Santo/Santa" (Logic sederhana: Santo dulu)
            if ($en == 'Saint') {
                // Biasanya nama wanita mengandung a/e di akhir, tapi sulit dideteksi otomatis
                // Kita defaultkan "Santo/a" atau biarkan "St."
                $text = str_replace('Saint', 'St.', $text); 
            } else {
                $text = str_replace($en, $id, $text);
            }
        }

        // 6. Rapikan "of" yang tersisa
        $text = str_replace(' of ', ' ', $text);

        return $text;
    }

    private function toRoman($number) {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}