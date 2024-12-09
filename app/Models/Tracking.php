<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'status', 'location'
    ];

    // Relasi dengan Order: Setiap Tracking milik satu Order
    public function order()
    {
        return $this->belongsTo(Order::class);  // Relasi belongsTo
    }
}


