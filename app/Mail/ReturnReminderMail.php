<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan; // Pastikan Model Loan dipanggil

class ReturnReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat: Batas Waktu Pengembalian Barang Inventaris',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}