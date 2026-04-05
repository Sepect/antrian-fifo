<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Screening;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Setup Admin Account
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Setup Nurse Account
        User::create([
            'name' => 'Jane Smith',
            'email' => 'perawat@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'perawat',
        ]);

        // 3. Setup Default Master Screenings for Priority Queueing
        $screenings = [
            ['name' => 'Sesak Napas Berat / Henti Napas', 'weight' => 15, 'category' => 'Respirasi'],
            ['name' => 'Pendarahan Aktif Massive', 'weight' => 12, 'category' => 'Trauma'],
            ['name' => 'Nyeri Dada Tertekan Ekstrim', 'weight' => 10, 'category' => 'Kardiovaskuler'],
            ['name' => 'Penurunan Kesadaran / Koma', 'weight' => 15, 'category' => 'Neurologi'],
            ['name' => 'Demam Tinggi > 40°C', 'weight' => 5, 'category' => 'Infeksi'],
            ['name' => 'Pusing Kepala Ringan', 'weight' => 1, 'category' => 'Neurologi'],
            ['name' => 'Luka Lecet / Luka Ringan', 'weight' => 1, 'category' => 'Trauma'],
            ['name' => 'Mual / Muntah', 'weight' => 3, 'category' => 'Gastrointestinal'],
        ];

        foreach ($screenings as $s) {
            Screening::create($s);
        }
    }
}
