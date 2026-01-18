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
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}">
                    <i class="bi bi-sort-alpha-down me-2"></i>Judul A-Z
                </a></li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'title_desc']) }}">
                    <i class="bi bi-sort-alpha-up me-2"></i>Judul Z-A
                </a></li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'author']) }}">
                    <i class="bi bi-person me-2"></i>Pengarang A-Z
                </a></li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">
                    <i class="bi bi-calendar-plus me-2"></i>Terbaru
                </a></li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'available']) }}">
                    <i class="bi bi-check-circle me-2"></i>Tersedia
                </a></li>
            </ul>
        </div>
    </div>
    
    <!-- Book Grid -->
    @if($books->count() > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden">
                        <!-- Book Cover -->
                        <div class="position-relative">
                            @if($book->cover_image_url)
                                <img src="{{ $book->cover_image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $book->title }}"
                                     style="height: 200px; object-fit: cover; width: 100%;">
                            @else
                                <div class="book-cover-placeholder d-flex flex-column align-items-center justify-content-center" 
                                     style="height: 200px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                                    <i class="bi bi-journal-bookmark display-4 text-muted mb-2"></i>
                                    <small class="text-muted text-center px-2">{{ $book->title }}</small>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-info bg-opacity-75 text-white">
                                    {{ $book->category->name ?? 'Umum' }}
                                </span>
                            </div>
                            
                            <!-- Availability Badge -->
                            <div class="position-absolute top-0 start-0 m-2">
                                @if($book->available_copies > 0)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <!-- Book Title -->
                            <h6 class="card-title fw-bold mb-2 line-clamp-2" title="{{ $book->title }}">
                                {{ Str::limit($book->title, 50) }}
                            </h6>
                            
                            <!-- Book Author -->
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-person me-1"></i>
                                <span class="line-clamp-1" title="{{ $book->author }}">
                                    {{ Str::limit($book->author, 30) }}
                                </span>
                            </p>
                            
                            <!-- Book Details -->
                            <div class="mt-auto">
                                <div class="row small text-muted mb-2">
                                    <div class="col-6">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $book->publication_year ?? '-' }}
                                    </div>
                                    <div class="col-6 text-end">
                                        <i class="bi bi-copy me-1"></i>
                                        {{ $book->available_copies }}/{{ $book->total_copies }}
                                    </div>
                                </div>
                                
                                <!-- ISBN -->
                                <p class="card-text small text-muted mb-3">
                                    <i class="bi bi-upc-scan me-1"></i>
                                    <code class="small">{{ $book->isbn }}</code>
                                </p>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('catalog.show', $book) }}" 
                                   class="btn btn-outline-primary btn-sm px-3">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                                
                                <!-- Loan Button -->
                                @if($book->available_copies > 0 && auth()->check() && !auth()->user()->is_admin)
                                    <form action="{{ route('loans.store') }}" method="POST" class="mb-0">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                        <input type="hidden" name="borrow_date" value="{{ now()->toDateString() }}">
                                        <input type="hidden" name="due_date" value="{{ now()->addDays(7)->toDateString() }}">
                                        <button type="submit" 
                                                class="btn btn-primary btn-sm px-3"
                                                onclick="return confirm('Pinjam buku: {{ $book->title }}?')">
                                            <i class="bi bi-cart-plus me-1"></i>Pinjam
                                        </button>
                                    </form>
                                @elseif($book->available_copies <= 0)
                                    <button class="btn btn-secondary btn-sm px-3" disabled>
                                        <i class="bi bi-ban me-1"></i>Habis
                                    </button>
                                @elseif(!auth()->check())
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                    </a>
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
                    {{ $books->withQueryString()->links() }}
                </ul>
            </nav>
        </div>
    @endif
</div>

@push('styles')
<style>
    .card-hover {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e9ecef;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #0d6efd;
    }
    
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .book-cover-placeholder {
        transition: all 0.3s;
    }
    
    .card-hover:hover .book-cover-placeholder {
        background: linear-gradient(135deg, #e3e8f0 0%, #a8b4d0 100%) !important;
    }
    
    /* Pagination custom styling */
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .pagination .page-link {
        color: #0d6efd;
    }
    
    .pagination .page-link:hover {
        color: #0a58ca;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit filter when category changes (optional)
        const categorySelect = document.querySelector('select[name="category"]');
        const searchInput = document.querySelector('input[name="search"]');
        
        // Debounce function untuk search
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    // Auto submit jika lebih dari 3 karakter
                    if (e.target.value.length === 0 || e.target.value.length >= 3) {
                        e.target.form.submit();
                    }
                }, 500);
            });
        }
        
        // Category change auto-submit
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        // Highlight active sort option
        const currentSort = new URLSearchParams(window.location.search).get('sort');
        const sortLinks = document.querySelectorAll('.dropdown-item');
        
        sortLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href.includes(`sort=${currentSort}`)) {
                link.classList.add('active');
                link.innerHTML = `<i class="bi bi-check2 me-2"></i>${link.textContent}`;
            }
        });
        
        // Add loading state to loan buttons
        const loanForms = document.querySelectorAll('form[action*="loans.store"]');
        loanForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                if (button) {
                    button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
                    button.disabled = true;
                }
            });
        });
    });
</script>
@endpush
@endsection