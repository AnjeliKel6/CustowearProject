<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'contacts';

    // Kolom yang dapat diisi
    protected $fillable = [
        'name',
        'phone',
        'email',
        'comment',
        'attachment', // Kolom untuk file yang diunggah
    ];

    // Tentukan tipe data yang akan di-cast otomatis
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Aksesor untuk mendapatkan URL lengkap file attachment.
     */
    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? asset('storage/' . $this->attachment) : null;
    }
}
