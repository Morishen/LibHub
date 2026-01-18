<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi (mass assignable).
     * Diselaraskan agar admin bisa mengelola kategori melalui dashboard.
     */
    protected $fillable = [
        'name',
        'slug', // Tambahan: berguna untuk filter kategori di URL katalog (misal: /catalog?category=novel)
    ];

    /**
     * Relasi ke Book (Satu kategori memiliki banyak buku).
     * Digunakan untuk memanggil $category->books di halaman kategori.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}