@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('catalog.index') }}" class="text-decoration-none">
                    <i class="bi bi-house-door me-1"></i>Katalog
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ Str::limit($book->title, 30) }}
            </li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Book Cover and Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <!-- Book Cover Placeholder -->
                    <div class="bg-light rounded p-5 mb-4">
                        <i class="bi bi-journal-bookmark display-1 text-muted"></i>
                    </div>
                    
                    <!-- Availability Status -->
                    @if($book->available_copies > 0)
                        <div class="alert alert-success">
                            <h6 class="alert-heading mb-2">
                                <i class="bi bi-check-circle me-2"></i>Tersedia
                            </h6>
                            <p class="mb-0">
                                <strong>{{ $book->available_copies }}</strong> dari 
                                <strong>{{ $book->total_copies }}</strong> kopi tersedia
                            </p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h6 class="alert-heading mb-2">
                                <i class="bi bi-exclamation-triangle me-2"></i>Tidak Tersedia
                            </h6>
                            <p class="mb-0">Semua kopi sedang dipinjam</p>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        @if($book->available_copies > 0 && auth()->check() && !auth()->user()->is_admin)
                            <form action="{{ route('loans.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-cart-plus me-2"></i>Pinjam Buku Ini
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Book Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-3">{{ $book->title }}</h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150" class="text-muted">Pengarang</th>
                                    <td>{{ $book->author }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Penerbit</th>
                                    <td>{{ $book->publisher ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tahun Terbit</th>
                                    <td>{{ $book->publication_year ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150" class="text-muted">ISBN</th>
                                    <td>{{ $book->isbn }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Kategori</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $book->category->name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Stok Total</th>
                                    <td>{{ $book->total_copies }} kopi</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="mb-3">Deskripsi Buku</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                @if($book->description)
                                    <p class="mb-0">{{ $book->description }}</p>
                                @else
                                    <p class="mb-0 text-muted">Tidak ada deskripsi tersedia</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loan History (for admins) -->
                    @if(auth()->check() && auth()->user()->is_admin)
                        <div class="mt-5">
                            <h5 class="mb-3">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terakhir
                            </h5>
                            @if(isset($recentLoans) && $recentLoans->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nama Peminjam</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Batas Kembali</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentLoans as $loan)
                                                <tr>
                                                    <td>{{ $loan->user->name }}</td>
                                                    <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                                                    <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($loan->returned_at)
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                                            </span>
                                                        @elseif($loan->is_overdue)
                                                            <span class="badge bg-danger">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>Terlambat
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">
                                                                <i class="bi bi-clock me-1"></i>Dipinjam
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>Belum ada riwayat peminjaman untuk buku ini
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection