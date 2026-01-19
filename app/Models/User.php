<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',    
        'address',  
        'is_admin', 
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom ke tipe data tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean', // Memastikan 0/1 dibaca sebagai true/false
    ];

    /**
     * Relasi ke loans (peminjaman buku).
     */
    public function loans()
    {
        // Menggunakan string path lengkap untuk menghindari error jika class belum di-import
        return $this->hasMany(\App\Models\Loan::class);
    }

    /**
     * Helper: cek apakah user adalah admin.
     * Menggunakan casting boolean dari properti agar lebih konsisten.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->is_admin === 1;
    }
}