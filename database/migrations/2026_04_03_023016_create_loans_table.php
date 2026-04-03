<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users dan items (Otomatis hapus data jika user/barang dihapus)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            
            // Tanggal Transaksi
            $table->dateTime('loan_date'); // Rencana ambil barang
            $table->dateTime('due_date');  // Rencana kembali barang
            $table->dateTime('return_date')->nullable(); // Tanggal aktual kembali
            
            // Status Peminjaman
            $table->enum('status', [
                'Pending',   // Menunggu persetujuan petugas
                'Approved',  // Disetujui, barang siap diambil
                'Active',    // Barang sedang dibawa warga
                'Returned',  // Barang sudah dikembalikan
                'Rejected',  // Ditolak oleh petugas
                'Overdue'    // Terlambat dikembalikan
            ])->default('Pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
