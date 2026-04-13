<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory; 

    protected $fillable = [
        'item_code',
        'name',
        'category',
        'condition',
        'status',
        'location',
        'image_url',
        'stock',
    ];

    // Contoh Accessor di app/Models/Item.php
public function getIsBookableAttribute()
{
    // Barang hanya bisa dipinjam jika status fisiknya 'Tersedia' DAN stok > 0
    return $this->status === 'Tersedia' && $this->stock > 0;
}
}