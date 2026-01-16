@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="container">
    <h2 class="mb-4">
        <i class="bi bi-book me-2"></i>Katalog Buku
    </h2>
    
    <!-- Search and Filter Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('catalog.index') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Cari judul, pengarang, atau ISBN..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Results Count -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="mb-0 text-muted">
            Menampilkan <strong>{{ $books->count() }}</strong> dari <strong>{{ $books->total() }}</strong> buku
        </p>
        
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-sort-down me-1"></i>Urutkan
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?sort=title">Judul A-Z</a></li>
                <li><a class="dropdown-item" href="?sort=title_desc">Judul Z-A</a></li>
                <li><a class="dropdown-item" href="?sort=author">Pengarang A-Z</a></li>
                <li><a class="dropdown-item" href="?sort=newest">Terbaru</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Book Grid -->
    @if($books->count() > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card h-100 card-hover border-0 shadow-sm">
                        <div class="card-body">
                            <!-- Book Cover Placeholder -->
                            <div class="text-center mb-3">
                                <div class="bg-light rounded p-4 mx-auto" style="max-width: 150px;">
                                    <i class="bi bi-journal-bookmark display-6 text-muted"></i>
                                </div>
                            </div>
                            
                            <!-- Book Info -->
                            <h6 class="card-title fw-bold">{{ $book->title }}</h6>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-person me-1"></i>{{ $book->author }}
                            </p>
                            <p class="card-text small mb-2">
                                <i class="bi bi-tag me-1"></i>{{ $book->category->name }}
                            </p>
                            
                            <!-- Availability Status -->
                            <div class="mb-3">
                                @if($book->available_copies > 0)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia ({{ $book->available_copies }})
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Tidak Tersedia
                                    </span>
                                @endif
                            </div>
                            
                            <!-- ISBN -->
                            <p class="card-text small text-muted mb-0">
                                ISBN: {{ $book->isbn }}
                            </p>
                        </div>
                        
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('catalog.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                                
                                @if($book->available_copies > 0 && auth()->check() && !auth()->user()->is_admin)
                                    <form action="{{ route('loans.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-cart-plus me-1"></i>Pinjam
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="bi bi-book display-1 text-muted"></i>
            </div>
            <h4 class="text-muted mb-3">Tidak ada buku ditemukan</h4>
            <p class="text-muted mb-4">Coba gunakan kata kunci atau filter yang berbeda</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-clockwise me-1"></i>Reset Pencarian
            </a>
        </div>
    @endif
    
    <!-- Pagination -->
    @if($books->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{ $books->links() }}
                </ul>
            </nav>
        </div>
    @endif
</div>
@endsection