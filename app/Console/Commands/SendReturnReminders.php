<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use App\Mail\ReturnReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReturnReminders extends Command
{
    // Ini adalah nama perintah yang akan kita jalankan di terminal
    protected $signature = 'app:send-return-reminders';

    protected $description = 'Mengirim email pengingat pengembalian barang yang batas waktunya besok';

    public function handle()
    {
        // 1. Cari tanggal besok
        $tomorrow = Carbon::tomorrow()->toDateString();

        // 2. Cari transaksi yang statusnya 'Active' (sedang dipinjam) DAN due_date-nya besok
        $loans = Loan::with(['user', 'details.item'])
                    ->where('status', 'Active')
                    ->whereDate('due_date', $tomorrow)
                    ->get();

        $count = 0;

        // 3. Looping dan kirim email ke masing-masing peminjam
        foreach ($loans as $loan) {
            Mail::to($loan->user->email)->send(new ReturnReminderMail($loan));
            $count++;
        }

        // Tampilkan pesan sukses di terminal
        $this->info("Berhasil mengirim {$count} email pengingat!");
    }
}