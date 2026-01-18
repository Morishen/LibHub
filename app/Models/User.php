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
        return $this->hasMany(Loan::class);
    }

    /**

     * Helper: cek apakah user adalah admin.
     * Diperbarui sesuai kolom 'is_admin' di database Anda.
     */
    public function isAdmin(): bool
    {
        // Mengembalikan true jika is_admin bernilai 1
        return (bool) $this->is_admin;
    }
}