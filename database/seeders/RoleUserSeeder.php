<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    public function run()
    {

        // 2. PENGURUS GEREJA (Akses Pengumuman, Wilayah, Kritik Saran)
        User::create([
            'name' => 'Pengurus Gereja',
            'email' => 'pengurus@gerejatemanggal.com',
            'password' => Hash::make('ignatiusloyola2026'),
            'role' => 'pengurus_gereja',
        ]);

        // 3. DIREKTUR MUSIK (Akses Mazmur, Padus, Organis)
        User::create([
            'name' => 'Koordinator Musik',
            'email' => 'musik@gerejatemanggal.com',
            'password' => Hash::make('musikTemanggal2026'),
            'role' => 'direktur_musik',
        ]);

        // 4. MISDINAR (Akses Jadwal Misdinar & Profil Diri)
        User::create([
            'name' => 'Misdinar Kalteng',
            'email' => 'misdinar@gerejatemanggal.com',
            'password' => Hash::make('misdinarkalteng'),
            'role' => 'misdinar',
        ]);

        // 5. LEKTOR (Akses Jadwal Lektor & Profil Diri)
        User::create([
            'name' => 'Lektor',
            'email' => 'lektor@gerejatemanggal.com',
            'password' => Hash::make('lektortemanggal'),
            'role' => 'lektor',
        ]);
    }
}