@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-book me-2 text-primary"></i>
                {{ Auth::check() && Auth::user()->isAdmin() ? 'Manajemen Katalog' : 'Katalog Buku' }}
            </h2>
            <p class="text-muted">
                {{ Auth::check() && Auth::user()->isAdmin() ? 'Kelola koleksi buku perpustakaan Anda.' : 'Cari dan pinjam buku favorit Anda di sini.' }}
            </p>
        </div>

        @if(Auth::check() && Auth::user()->isAdmin())
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Buku Baru
            </a>
        @endif
    </div>
    
    <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('catalog.index') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" 
                               placeholder="Cari judul, pengarang, atau ISBN..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <select name="category" class="form-select border-start-0" id="categoryFilter">
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
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <p class="mb-0 text-muted small">
            Menampilkan <strong>{{ $books->count() }}</strong> dari <strong>{{ $books->total() }}</strong> buku ditemukan
        </p>
        
        <div class="dropdown">
            <button class="btn btn-white border shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-sort-down me-1"></i> Urutkan Berdasarkan
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Terbaru</a></li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}">Judul A-Z</a></li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'available']) }}">Tersedia</a></li>
            </ul>
        </div>
    </div>
    
    @if($books->count() > 0)
        <div class="row g-4">
            @foreach($books as $book)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden position-relative">
                        
                        <div class="position-absolute top-0 end-0 m-2 z-index-2 d-flex flex-column gap-1 align-items-end">
                            <span class="badge bg-white text-dark shadow-sm border">
                                {{ $book->category->name ?? 'Umum' }}
                            </span>
                            @if($book->available_copies > 0)
                                <span class="badge bg-success shadow-sm">Tersedia</span>
                            @else
                                <span class="badge bg-danger shadow-sm">Habis</span>
                            @endif
                        </div>

                        <div class="cover-wrapper">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $book->title }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex flex-column align-items-center justify-content-center" 
                                     style="height: 250px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <i class="bi bi-journal-bookmark display-4 text-secondary opacity-25"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="card-title fw-bold mb-1 text-truncate" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h6>
                            <p class="text-muted small mb-3">Oleh: {{ $book->author }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between small text-muted mb-2 border-top pt-2">
                                    <span><i class="bi bi-calendar3 me-1"></i> {{ $book->publication_year }}</span>
                                    <span><i class="bi bi-stack me-1"></i> Stok: {{ $book->available_copies }}</span>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('catalog.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-info-circle me-1"></i> Detail
                                    </a>

                                    @auth
                                        @if(Auth::user()->isAdmin())
                                            <div class="btn-group w-100">
                                                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline flex-fill">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100 rounded-start-0" 
                                                            onclick="return confirm('Hapus buku ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            @if($book->available_copies > 0)
                                                <form action="{{ route('loans.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                    <button type="submit" class="btn btn-primary btn-sm w-100" 
                                                            onclick="return confirm('Pinjam buku ini?')">
                                                        <i class="bi bi-cart-plus me-1"></i> Pinjam
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>Stok Habis</button>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-light border btn-sm">
                                            Login untuk Pinjam
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 border rounded-3 bg-light mt-4">
            <i class="bi bi-search display-3 text-muted mb-3 d-block"></i>
            <h4 class="text-muted">Buku tidak ditemukan</h4>
            <p class="text-muted">Coba cari dengan judul atau kategori yang lain.</p>
        </div>
    @endif
    
    <div class="d-flex justify-content-center mt-5">
        {{ $books->withQueryString()->links() }}
    </div>
</div>

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .cover-wrapper {
        overflow: hidden;
    }
    .card-hover:hover img {
        transform: scale(1.05);
        transition: transform 0.5s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto submit filter kategori ketika berubah
        const filter = document.getElementById('categoryFilter');
        if(filter) {
            filter.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>
@endpush
@endsection