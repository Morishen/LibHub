<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'returned_at',
    ];

    /**
     * Casting tanggal sangat penting agar di file Blade (Tampilan)
     * Anda bisa langsung menggunakan {{ $loan->borrow_date->format('d M Y') }}
     */
    protected $casts = [
        'borrow_date' => 'date',
        'due_date'    => 'date',
        'returned_at' => 'datetime',
    ];

    // --- RELASI ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // --- HELPER & LOGIKA ---

    public function isReturned(): bool
    {
        return !is_null($this->returned_at);
    }

    /**
     * Accessor: Menentukan status peminjaman dalam bentuk teks.
     * Sangat berguna untuk badge warna di dashboard.
     */
    public function getStatusAttribute(): string
    {
        if ($this->isReturned()) {
            return 'Returned';
        }
        return now()->gt($this->due_date) ? 'Overdue' : 'Active';
    }

    /**
     * Accessor: Cek apakah sudah lewat jatuh tempo.
     */
    public function getIsOverdueAttribute(): bool
    {
        return !$this->isReturned() && now()->gt($this->due_date);
    }

    /**
     * Accessor: Menghitung selisih hari keterlambatan.
     */
    public function getDaysOverdueAttribute(): int
    {
        if ($this->is_overdue) {
            return now()->diffInDays($this->due_date);
        }
        return 0;
    }

    // --- SCOPES (PENTING UNTUK DASHBOARD) ---

    /**
     * Memudahkan filter data di Controller: Loan::active()->get();
     */
    public function scopeActive($query)
    {
        return $query->whereNull('returned_at')->whereDate('due_date', '>=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->whereNull('returned_at')->whereDate('due_date', '<', now());
    }
}