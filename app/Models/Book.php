<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi (mass assignable).
     * Diselaraskan dengan kebutuhan input di AdminBookController dan proses peminjaman.
     */
    protected $fillable = [
        'isbn',
        'title',
        'author',
        'publisher',
        'publication_year',
        'description',
        'category_id',
        'total_copies',
        'available_copies', // Penting: harus ada agar bisa di-update saat ada peminjaman
        'cover_image',
    ];

    /**
     * Relasi ke Category
     * Memungkinkan pemanggilan $book->category->name di katalog.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Loan
     * Menghubungkan buku dengan banyak riwayat peminjaman.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Helper: Cek ketersediaan buku.
     * Digunakan di LoanController sebelum proses store peminjaman.
     */
    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }
}