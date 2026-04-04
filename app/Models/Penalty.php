<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = ['loan_id', 'amount', 'payment_status'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}