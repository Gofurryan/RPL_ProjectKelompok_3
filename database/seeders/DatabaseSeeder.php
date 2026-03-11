<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
        'name' => 'Gofur Aryan',
        'email' => 'gofuraryann@gmail.com',
        'role' => 'petugas',
        'phone' => '+62859180375715',
        'password' => bcrypt('password123'),
    ]);

    \App\Models\User::create([
        'name' => 'Ryan',
        'email' => 'gofuryan35@gmail.com',
        'role' => 'warga',
        'phone' => '859180375715',
        'password' => bcrypt('password123'),
    ]);

    // Masukkan Data Items sesuai Figma
    \App\Models\Item::create([
        'item_code' => 'ELC-MIC-001',
        'name' => 'Mic Wireless Shure',
        'category' => 'Elektronik',
        'status' => 'Available',
        'location' => 'Ruang Operator'
    ]);
    }
}