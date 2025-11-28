<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Territory;
use App\Models\Community;
use App\Models\Lingkungan;
use Illuminate\Support\Str;

class TerritorySeeder extends Seeder
{
    public function run()
    {
        // Data dari permintaan Anda
        $data = [
            'St. Aloysius Gonzaga' => [
                'St. Benediktus Perum Pertamina',
                'St. Skolastika Karangmojo',
                'St. Thomas Tundan',
                'St. Andreas Kujonsari'
            ],
            'St. Robertus Bellarminus' => [
                'St. Fransiscus Xaverius Prm Purwomartani',
                'St. Yohanes Rasul Purwo Baru',
                'St. Maria Sidokerto',
                'St. Elisabeth Sambisari'
            ],
            'St. Yohanes De Britto' => [
                'St. Yakobus Temanggal I',
                'St. Bernadetta Temanggal I',
                'St. Petrus Temanggal II',
                'St. Paulus Temanggal II'
            ],
            'St. Petrus Kanisius' => [
                'St. Yusup Tegalbojan',
                'St. Lukas Somodaran',
                'St. Theresia Tegalsari'
            ]
        ];

        foreach ($data as $wilayah => $lingkungans) {
            // 1. Buat Wilayah
            $newTerritory = Territory::create([
                'name' => $wilayah,
                'slug' => Str::slug($wilayah),
                'description' => 'Wilayah pelayanan ' . $wilayah
            ]);

            // 2. Buat Lingkungan di dalamnya
            foreach ($lingkungans as $lingkungan) {
                Lingkungan::create([
                    'territory_id' => $newTerritory->id,
                    'name' => $lingkungan,
                    'info' => 'Informasi seputar kegiatan dan jadwal di lingkungan ' . $lingkungan
                ]);
            }
        }
    }
}