<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;

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
        'name' => 'Ryan Warga',
        'email' => 'gofuryan35@gmail.com',
        'role' => 'warga',
        'phone' => '859180375715',
        'password' => bcrypt('password123'),
    ]);

    // 2. Daftar 20 Barang
        $daftarBarang = [
            ['name' => 'Mic Wireless Sony A1', 'category' => 'Elektronik', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Speaker Portable JBL', 'category' => 'Elektronik', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Proyektor Epson EB-X400', 'category' => 'Elektronik', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Kabel Roll 10 Meter', 'category' => 'Elektronik', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Genset Honda EU22i', 'category' => 'Elektronik', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Vacuum Cleaner Sharp', 'category' => 'Sarana Umum', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Karpet Sajadah Imam', 'category' => 'Perlengkapan Ibadah', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Mukena Inventaris Putih', 'category' => 'Perlengkapan Ibadah', 'status' => 'Available', 'condition' => 'good'],
            ['name' => "Al-Qur'an Madinah (Set 10)", 'category' => 'Perlengkapan Ibadah', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Rehal Kayu Jati (Set 5)', 'category' => 'Perlengkapan Ibadah', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Kursi Lipat Chitose', 'category' => 'Furniture', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Meja Lesehan Kayu', 'category' => 'Furniture', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Tenda Lipat 3x3m', 'category' => 'Sarana Umum', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Termos Air Panas 10L', 'category' => 'Sarana Umum', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Tangga Lipat Aluminium', 'category' => 'Sarana Umum', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Sound System Mixer Yamaha', 'category' => 'Elektronik', 'status' => 'Borrowed', 'condition' => 'good'],
            ['name' => 'Sajadah Panjang (Shaf)', 'category' => 'Perlengkapan Ibadah', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Papan Tulis Whiteboard', 'category' => 'Sarana Umum', 'status' => 'Available', 'condition' => 'good'],
            ['name' => 'Kipas Angin Berdiri', 'category' => 'Elektronik', 'status' => 'Available', 'condition' => 'fair'],
            ['name' => 'Kotak Infaq Stainless', 'category' => 'Sarana Umum', 'status' => 'Available', 'condition' => 'good'],
        ];

        // 3. Looping untuk memasukkan data ke Database secara otomatis
        foreach ($daftarBarang as $index => $item) {
            Item::create([
                // Membuat kode barang otomatis (contoh: ITM-001, ITM-002, dst)
                'item_code' => 'ITM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'name' => $item['name'],
                'category' => $item['category'],
                'status' => $item['status'],
                'condition' => $item['condition'],
                'location' => 'Gudang Utama', // Lokasi default
            ]);
        }
    }
}