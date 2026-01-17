@extends('layouts.app')

@section('title', 'Kelola Buku')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-book me-2"></i>Kelola Buku
            </h2>
            <p class="text-muted mb-0">Kelola koleksi buku perpustakaan</p>
        </div>
        <div>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Buku Baru
            </a>
            <!-- Optional: Import/Export buttons -->
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i>Import
            </button>
        </div>
    </div>
    
    <!-- Search & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.books.index') }}" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Cari judul/pengarang/ISBN..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
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
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>
                            Tidak Tersedia
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Urutkan</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul Z-A</option>
                        <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Pengarang</option>
                        <option value="available" {{ request('sort') == 'available' ? 'selected' : '' }}>Stok Tersedia</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                        <!-- Export Button -->
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Stats Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-primary mb-1">Total Buku</h6>
                            <h3 class="mb-0">{{ $stats['total_books'] ?? $books->total() }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary text-white rounded-circle">
                                <i class="bi bi-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-success mb-1">Tersedia</h6>
                            <h3 class="mb-0">{{ $stats['available_books'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-success text-white rounded-circle">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-warning mb-1">Dipinjam</h6>
                            <h3 class="mb-0">{{ $stats['borrowed_books'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-warning text-white rounded-circle">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 bg-danger bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-danger mb-1">Habis</h6>
                            <h3 class="mb-0">{{ $stats['unavailable_books'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-danger text-white rounded-circle">
                                <i class="bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($books->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th width="100">Cover</th>
                                <th width="120">ISBN</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th width="100">Stok</th>
                                <th width="100">Status</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $index => $book)
                                <tr class="{{ $book->available_copies == 0 ? 'table-danger-light' : '' }}">
                                    <td class="text-center text-muted">{{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}</td>
                                    
                                    <!-- Book Cover -->
                                    <td>
                                        @if($book->cover_image_url)
                                            <img src="{{ $book->cover_image_url }}" 
                                                 class="img-thumbnail" 
                                                 alt="{{ $book->title }}"
                                                 style="width: 60px; height: 80px; object-fit: cover; cursor: pointer;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal{{ $book->id }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="width: 60px; height: 80px; cursor: pointer;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal{{ $book->id }}">
                                                <i class="bi bi-journal text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- ISBN -->
                                    <td>
                                        <code class="small" title="{{ $book->isbn }}">
                                            {{ Str::limit($book->isbn, 12) }}
                                        </code>
                                        <button class="btn btn-sm btn-outline-secondary btn-copy ms-1" 
                                                data-text="{{ $book->isbn }}"
                                                title="Salin ISBN">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </td>
                                    
                                    <!-- Book Title -->
                                    <td>
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <strong class="d-block mb-1" title="{{ $book->title }}">
                                                    {{ Str::limit($book->title, 40) }}
                                                </strong>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $book->publication_year ?? '-' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Author -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-light text-primary rounded-circle">
                                                    {{ substr($book->author, 0, 1) }}
                                                </div>
                                            </div>
                                            <span title="{{ $book->author }}">
                                                {{ Str::limit($book->author, 20) }}
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <!-- Category -->
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $book->category->name }}
                                        </span>
                                    </td>
                                    
                                    <!-- Stock -->
                                    <td>
                                        <div class="progress" style="height: 6px;" title="{{ $book->available_copies }} tersedia dari {{ $book->total_copies }}">
                                            @php
                                                $percentage = $book->total_copies > 0 ? ($book->available_copies / $book->total_copies) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar {{ $percentage > 30 ? 'bg-success' : ($percentage > 0 ? 'bg-warning' : 'bg-danger') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            {{ $book->available_copies }}/{{ $book->total_copies }}
                                        </small>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td>
                                        @if($book->available_copies > 0)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Habis
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('catalog.show', $book) }}" 
                                               class="btn btn-outline-info" 
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.books.edit', $book) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Edit Buku">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.books.destroy', $book) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirmDelete('{{ addslashes($book->title) }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger" 
                                                        title="Hapus Buku">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Image Modal for each book -->
                                <div class="modal fade" id="imageModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $book->title }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                @if($book->cover_image_url)
                                                    <img src="{{ $book->cover_image_url }}" 
                                                         class="img-fluid rounded" 
                                                         alt="{{ $book->title }}"
                                                         style="max-height: 70vh;">
                                                @else
                                                    <div class="py-5">
                                                        <i class="bi bi-journal-bookmark display-1 text-muted"></i>
                                                        <p class="text-muted mt-3">Tidak ada gambar cover</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Results Count & Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div>
                        <p class="text-muted mb-0">
                            Menampilkan <strong>{{ $books->firstItem() ?? 0 }}</strong> - 
                            <strong>{{ $books->lastItem() ?? 0 }}</strong> dari 
                            <strong>{{ $books->total() }}</strong> buku
                        </p>
                    </div>
                    
                    <!-- Pagination -->
                    @if($books->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{ $books->withQueryString()->links() }}
                            </ul>
                        </nav>
                    @endif
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-book display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Tidak ada buku ditemukan</h4>
                    <p class="text-muted mb-4">Mulai tambah buku pertama Anda</p>
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Buku Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-upload me-2"></i>Import Buku
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.books.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            Format: ISBN, Judul, Pengarang, Penerbit, Tahun, Kategori, Deskripsi, Jumlah
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Pastikan format sesuai template. 
                        <a href="{{ asset('templates/books_template.xlsx') }}" class="text-primary">Download Template</a></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-download me-2"></i>Export Buku
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.books.export') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select name="format" class="form-select">
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data</label>
                        <select name="data_type" class="form-select">
                            <option value="current">Data yang difilter</option>
                            <option value="all">Semua data</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-danger-light {
        background-color: rgba(220, 53, 69, 0.05);
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .btn-copy {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(bookTitle) {
        return confirm(`Apakah Anda yakin ingin menghapus buku:\n"${bookTitle}"?\n\nTindakan ini tidak dapat dibatalkan.`);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Copy ISBN functionality
        document.querySelectorAll('.btn-copy').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const text = this.getAttribute('data-text');
                
                navigator.clipboard.writeText(text).then(() => {
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    
                    // Change icon to check mark
                    icon.className = 'bi bi-check';
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-outline-success');
                    
                    // Revert after 2 seconds
                    setTimeout(() => {
                        icon.className = originalClass;
                        this.classList.remove('btn-outline-success');
                        this.classList.add('btn-outline-secondary');
                    }, 2000);
                });
            });
        });
        
        // Auto-submit filter on sort change
        const sortSelect = document.querySelector('select[name="sort"]');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        // Quick status filter buttons
        const statusButtons = document.querySelectorAll('.quick-filter');
        statusButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const status = this.getAttribute('data-status');
                const form = document.querySelector('form[action*="books.index"]');
                const statusSelect = form.querySelector('select[name="status"]');
                
                if (statusSelect) {
                    statusSelect.value = status;
                    form.submit();
                }
            });
        });
        
        // Bulk action functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const bookCheckboxes = document.querySelectorAll('.book-checkbox');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                bookCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    });
</script>
@endpush
@endsection