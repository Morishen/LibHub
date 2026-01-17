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
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <!-- Book Cover -->
                    <div class="text-center mb-4">
                        @if($book->cover_image_url)
                            <img src="{{ $book->cover_image_url }}" 
                                 class="img-fluid rounded shadow" 
                                 alt="{{ $book->title }}"
                                 style="max-height: 300px; object-fit: contain;">
                        @else
                            <div class="book-cover-placeholder d-flex flex-column align-items-center justify-content-center p-4" 
                                 style="height: 300px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 8px;">
                                <i class="bi bi-journal-bookmark display-3 text-muted mb-3"></i>
                                <p class="text-muted text-center small px-3">{{ $book->title }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Book Quick Info -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">ISBN:</span>
                            <code>{{ $book->isbn }}</code>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Kategori:</span>
                            <span class="badge bg-secondary">{{ $book->category->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tahun:</span>
                            <span>{{ $book->publication_year ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <!-- Availability Status -->
                    @if($book->available_copies > 0)
                        <div class="alert alert-success border-0 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-check-circle-fill fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading mb-1">Tersedia untuk Dipinjam</h6>
                                    <p class="mb-0">
                                        <strong>{{ $book->available_copies }}</strong> dari 
                                        <strong>{{ $book->total_copies }}</strong> kopi tersedia
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading mb-1">Tidak Tersedia</h6>
                                    <p class="mb-0">Semua kopi sedang dipinjam</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="mt-auto">
                        @if($book->available_copies > 0)
                            @auth
                                @if(!auth()->user()->is_admin)
                                    <form action="{{ route('loans.store') }}" method="POST" id="loanForm">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" 
                                                class="btn btn-primary btn-lg w-100 mb-3"
                                                onclick="return confirmLoan()">
                                            <i class="bi bi-cart-plus me-2"></i>Pinjam Buku Ini
                                        </button>
                                    </form>
                                    
                                    <!-- Loan Details Info -->
                                    <div class="alert alert-info border-0 small mb-3">
                                        <div class="d-flex">
                                            <i class="bi bi-info-circle me-2"></i>
                                            <div>
                                                <strong>Durasi Pinjam:</strong> 7 hari<br>
                                                <strong>Batas Kembali:</strong> {{ now()->addDays(7)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-secondary border-0 mb-3">
                                        <i class="bi bi-person-badge me-2"></i>
                                        Anda login sebagai admin
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100 mb-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Meminjam
                                </a>
                            @endauth
                        @else
                            <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                                <i class="bi bi-ban me-2"></i>Stok Habis
                            </button>
                            
                            <!-- Notify Me Button (Optional) -->
                            <button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#notifyModal">
                                <i class="bi bi-bell me-2"></i>Ingatkan Saat Stok Tersedia
                            </button>
                        @endif
                        
                        <!-- Back Button -->
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Book Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <!-- Book Header -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h1 class="card-title mb-2">{{ $book->title }}</h1>
                            <p class="text-muted lead mb-0">
                                <i class="bi bi-person me-1"></i>{{ $book->author }}
                            </p>
                        </div>
                        
                        <!-- Admin Actions -->
                        @if(auth()->check() && auth()->user()->is_admin)
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.books.edit', $book) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit Buku
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" 
                                                    onclick="return confirm('Hapus buku {{ $book->title }}?')">
                                                <i class="bi bi-trash me-2"></i>Hapus Buku
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Book Details Grid -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon">
                                        <i class="bi bi-building text-primary"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted d-block">Penerbit</small>
                                        <strong>{{ $book->publisher ?? 'Tidak tercantum' }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon">
                                        <i class="bi bi-tag text-primary"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted d-block">Kategori</small>
                                        <strong>{{ $book->category->name }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon">
                                        <i class="bi bi-calendar text-primary"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted d-block">Tahun Terbit</small>
                                        <strong>{{ $book->publication_year ?? 'Tidak diketahui' }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon">
                                        <i class="bi bi-stack text-primary"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted d-block">Stok Buku</small>
                                        <strong>
                                            <span class="text-success">{{ $book->available_copies }}</span> tersedia
                                            dari {{ $book->total_copies }} total
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-5">
                        <h5 class="mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-2"></i>Deskripsi Buku
                        </h5>
                        @if($book->description)
                            <div class="book-description p-4 bg-light rounded">
                                <p class="mb-0" style="line-height: 1.8;">{{ $book->description }}</p>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-card-text display-4 text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada deskripsi tersedia</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Loan History (for admins) -->
                    @if(auth()->check() && auth()->user()->is_admin)
                        <div class="mt-5 pt-4 border-top">
                            <h5 class="mb-3">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terakhir
                            </h5>
                            @if(isset($recentLoans) && $recentLoans->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nama Peminjam</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Batas Kembali</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentLoans as $loan)
                                                <tr class="{{ $loan->is_overdue ? 'table-danger' : '' }}">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm me-2">
                                                                <div class="avatar-title bg-light text-primary rounded-circle">
                                                                    {{ substr($loan->user->name, 0, 1) }}
                                                                </div>
                                                            </div>
                                                            {{ $loan->user->name }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                                                    <td>
                                                        {{ $loan->due_date->format('d/m/Y') }}
                                                        @if($loan->is_overdue)
                                                            <span class="badge bg-danger ms-2">+{{ $loan->due_date->diffInDays(now()) }} hari</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($loan->returned_at)
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                                            </span>
                                                            <small class="d-block text-muted">
                                                                {{ $loan->returned_at->format('d/m/Y') }}
                                                            </small>
                                                        @elseif($loan->is_overdue)
                                                            <span class="badge bg-danger">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>Terlambat
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-clock me-1"></i>Dipinjam
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!$loan->returned_at)
                                                            <button class="btn btn-sm btn-outline-success" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#returnModal{{ $loan->id }}">
                                                                <i class="bi bi-check-circle"></i> Kembalikan
                                                            </button>
                                                        @else
                                                            <span class="text-muted small">Selesai</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('admin.loans.index') }}?book={{ $book->id }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        Lihat Semua Peminjaman
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-info border-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle me-3 fs-4"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">Belum ada riwayat peminjaman</h6>
                                            <p class="mb-0">Buku ini belum pernah dipinjam</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notify Modal -->
<div class="modal fade" id="notifyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-bell me-2"></i>Ingatkan Saya
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('notifications.subscribe') }}">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <div class="modal-body">
                    <p>Kami akan mengirimkan notifikasi ketika buku <strong>{{ $book->title }}</strong> tersedia untuk dipinjam.</p>
                    <div class="mb-3">
                        <label for="notify_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="notify_email" name="email" 
                               value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .book-cover-placeholder {
        transition: all 0.3s;
    }
    
    .book-cover-placeholder:hover {
        background: linear-gradient(135deg, #e3e8f0 0%, #a8b4d0 100%) !important;
    }
    
    .book-description {
        white-space: pre-line;
        text-align: justify;
    }
    
    .info-card {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .info-card:hover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, 0.1);
        border-radius: 8px;
        font-size: 1.2rem;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
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
</style>
@endpush

@push('scripts')
<script>
    function confirmLoan() {
        const bookTitle = "{{ $book->title }}";
        const dueDate = "{{ now()->addDays(7)->format('d F Y') }}";
        
        return confirm(
            `Pinjam buku "${bookTitle}"?\n\n` +
            `Buku harus dikembalikan sebelum:\n${dueDate}\n\n` +
            `Apakah Anda yakin ingin meminjam?`
        );
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const loanForm = document.getElementById('loanForm');
        if (loanForm) {
            loanForm.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                if (button) {
                    button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
                    button.disabled = true;
                }
            });
        }
        
        // Auto focus notify modal email field
        const notifyModal = document.getElementById('notifyModal');
        if (notifyModal) {
            notifyModal.addEventListener('shown.bs.modal', function() {
                const emailInput = this.querySelector('#notify_email');
                if (emailInput) {
                    emailInput.focus();
                }
            });
        }
        
        // Copy ISBN to clipboard
        const isbnElement = document.querySelector('code');
        if (isbnElement) {
            isbnElement.addEventListener('click', function() {
                const isbn = this.textContent;
                navigator.clipboard.writeText(isbn).then(() => {
                    const originalText = this.textContent;
                    this.textContent = 'Copied!';
                    this.classList.add('text-success');
                    
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.classList.remove('text-success');
                    }, 2000);
                });
            });
            
            isbnElement.style.cursor = 'pointer';
            isbnElement.title = 'Klik untuk menyalin ISBN';
        }
    });
</script>
@endpush
@endsection