<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    use HasFactory;
    
    // 1. Izinkan kolom-kolom ini diisi (Mencegah Mass Assignment Error)
    protected $fillable = [
        'loan_id',
        'item_id',
        'quantity',
    ];

    // 2. Relasi Balikan ke tabel Induk (Loan) -> Ini yang akan menyelesaikan error-mu
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // 3. Relasi ke tabel Barang (Item) -> Sangat berguna untuk nanti menampilkan nama barang di view
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
