<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoCancelExpiredLoans extends Command
{
    // Nama perintah yang akan dipanggil oleh sistem
    protected $signature = 'loans:auto-cancel';

    // Deskripsi perintah
    protected $description = 'Membatalkan otomatis peminjaman yang sudah di-Approve tapi tidak diambil lebih dari 2 hari';

    public function handle()
    {
        // 1. Cari transaksi dengan jalur mutlak (\App\Models\Loan dan \Carbon\Carbon)
        $expiredLoans = \App\Models\Loan::where('status', 'Approved')
                            ->where('updated_at', '<=', \Carbon\Carbon::now()->subDays(2))
                            ->get();

        $count = 0;

        // 2. Looping dan ubah statusnya menjadi 'Cancelled'
        foreach ($expiredLoans as $loan) {
            $loan->update([
                'status' => 'Cancelled'
            ]);
            $count++;
        }

        // 3. Pesan log di terminal
        $this->info("Berhasil membatalkan {$count} transaksi yang kadaluarsa.");
    }
}