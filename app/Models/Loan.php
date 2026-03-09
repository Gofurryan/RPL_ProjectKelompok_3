<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'item_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
    ];
}