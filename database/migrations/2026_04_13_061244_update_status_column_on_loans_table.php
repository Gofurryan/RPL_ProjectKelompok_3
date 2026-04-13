<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah tipe kolom menjadi VARCHAR(255) agar lebih fleksibel
        // Sesuaikan nama tabel 'loans' jika nama tabel aslimu berbeda
        DB::statement("ALTER TABLE loans MODIFY COLUMN status VARCHAR(255) DEFAULT 'Pending'");
    }

    public function down(): void
    {
        // Kembalikan ke ENUM jika di-rollback (sesuaikan dengan status awalmu)
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('Pending', 'Approved', 'Active', 'Returned') DEFAULT 'Pending'");
    }
};