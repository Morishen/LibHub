@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container">
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
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        {{-- PERBAIKAN LOGIKA GAMBAR --}}
                        @php
                            $coverPath = 'storage/' . $book->cover_image;
                            $hasCover = $book->cover_image && file_exists(public_path($coverPath));
                        @endphp

                        @if($hasCover)
                            <img src="{{ asset($coverPath) }}" 
                                 class="img-fluid rounded shadow" 
                                 alt="{{ $book->title }}"
                                 style="max-height: 400px; width: 100%; object-fit: cover;">
                        @else
                            <div class="book-cover-placeholder d-flex flex-column align-items-center justify-content-center p-4" 
                                 style="height: 400px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 8px;">
                                <i class="bi bi-journal-bookmark display-3 text-muted mb-3"></i>
                                <p class="text-muted text-center small px-3">Gambar tidak tersedia untuk<br><strong>{{ $book->title }}</strong></p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-4 bg-light p-3 rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">ISBN:</span>
                            <code class="text-primary fw-bold">{{ $book->isbn }}</code>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Kategori:</span>
                            <span class="badge bg-info text-dark">{{ $book->category->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Tahun Terbit:</span>
                            <span class="fw-bold">{{ $book->publication_year ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        @if($book->available_copies > 0)
                            <div class="alert alert-success border-0 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Tersedia</h6>
                                        <small>{{ $book->available_copies }} dari {{ $book->total_copies }} kopi</small>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger border-0 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Sedang Dipinjam</h6>
                                        <small>Stok saat ini kosong</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-auto">
                        @if($book->available_copies > 0)
                            @auth
                                @if(!auth()->user()->is_admin)
                                    <form action="{{ route('loans.store') }}" method="POST" id="loanForm">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2 py-3 fw-bold shadow-sm" onclick="return confirmLoan()">
                                            <i class="bi bi-cart-plus me-2"></i>Pinjam Sekarang
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100 mb-2 py-3 fw-bold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Pinjam
                                </a>
                            @endauth
                        @else
                            <button class="btn btn-secondary btn-lg w-100 mb-2 py-3 fw-bold" disabled>
                                <i class="bi bi-hourglass-split me-2"></i>Stok Habis
                            </button>
                            <button class="btn btn-warning w-100 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#notifyModal">
                                <i class="bi bi-bell me-2"></i>Ingatkan Saya
                            </button>
                        @endif
                        
                        <a href="{{ route('catalog.index') }}" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="display-5 fw-bold mb-1">{{ $book->title }}</h1>
                            <p class="text-primary lead">Oleh: <strong>{{ $book->author }}</strong></p>
                        </div>
                        
                        @if(auth()->check() && auth()->user()->is_admin)
                            <div class="btn-group">
                                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="document.getElementById('delete-book-form').submit();">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-book-form" action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        @endif
                    </div>

                    <hr class="my-4 opacity-10">

                    <h5 class="fw-bold mb-3"><i class="bi bi-info-square me-2 text-primary"></i>Informasi Buku</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <small class="text-muted d-block">Penerbit</small>
                                <span class="fw-bold">{{ $book->publisher ?? 'Tidak tersedia' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <small class="text-muted d-block">Stok Koleksi</small>
                                <span class="fw-bold text-success">{{ $book->total_copies }} Buku</span>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3"><i class="bi bi-card-text me-2 text-primary"></i>Sinopsis / Deskripsi</h5>
                    <div class="bg-light p-4 rounded shadow-inner" style="min-height: 200px;">
                        @if($book->description)
                            <p style="line-height: 1.8; text-align: justify;">{{ $book->description }}</p>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-chat-left-dots display-4 d-block mb-3"></i>
                                <p>Belum ada deskripsi untuk buku ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection