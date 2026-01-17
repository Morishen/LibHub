@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-journal-check me-2"></i>Kelola Peminjaman
            </h2>
            <p class="text-muted mb-0">Kelola semua peminjaman anggota</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.loans.index') }}?status=overdue" class="btn btn-outline-danger">
                <i class="bi bi-exclamation-triangle me-1"></i>Terlambat
            </a>
            <a href="{{ route('admin.loans.index') }}?status=active" class="btn btn-outline-warning">
                <i class="bi bi-clock me-1"></i>Aktif
            </a>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.index') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Sedang Dipinjam
                        </option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>
                            Terlambat
                        </option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>
                            Dikembalikan
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari anggota atau judul buku..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" 
                           value="{{ request('date') }}" placeholder="Filter tanggal">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Peminjaman</h6>
                            <h3 class="mb-0">{{ $totalLoans }}</h3>
                        </div>
                        <div class="bg-primary text-white rounded-circle p-3">
                            <i class="bi bi-journal-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Aktif</h6>
                            <h3 class="mb-0">{{ $activeLoans }}</h3>
                        </div>
                        <div class="bg-warning text-white rounded-circle p-3">
                            <i class="bi bi-clock fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Terlambat</h6>
                            <h3 class="mb-0">{{ $overdueLoans }}</h3>
                        </div>
                        <div class="bg-danger text-white rounded-circle p-3">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Dikembalikan</h6>
                            <h3 class="mb-0">{{ $returnedLoans }}</h3>
                        </div>
                        <div class="bg-success text-white rounded-circle p-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loans Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($loans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong><br>
                                        <small class="text-muted">{{ $loan->user->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $loan->book->title }}</strong><br>
                                        <small class="text-muted">{{ $loan->book->author }}</small>
                                    </td>
                                    <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $loan->due_date->format('d/m/Y') }}
                                        @if($loan->is_overdue)
                                            <br><small class="text-danger">
                                                Terlambat {{ $loan->days_overdue }} hari
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($loan->returned_at)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                            </span>
                                            <br>
                                            <small>{{ $loan->returned_at->format('d/m/Y') }}</small>
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
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if(!$loan->returned_at)
                                                <form action="{{ route('admin.loans.return', $loan) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" 
                                                            onclick="return confirm('Tandai buku telah dikembalikan?')">
                                                        <i class="bi bi-check-lg"></i> Kembalikan
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal{{ $loan->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailModal{{ $loan->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Peminjaman</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <th width="150">Anggota</th>
                                                                <td>{{ $loan->user->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Email</th>
                                                                <td>{{ $loan->user->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Buku</th>
                                                                <td>{{ $loan->book->title }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>ISBN</th>
                                                                <td>{{ $loan->book->isbn }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Tanggal Pinjam</th>
                                                                <td>{{ $loan->borrow_date->format('d F Y') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Batas Kembali</th>
                                                                <td>{{ $loan->due_date->format('d F Y') }}</td>
                                                            </tr>
                                                            @if($loan->returned_at)
                                                                <tr>
                                                                    <th>Tanggal Kembali</th>
                                                                    <td>{{ $loan->returned_at->format('d F Y') }}</td>
                                                                </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Results Count -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="text-muted mb-0">
                        Menampilkan {{ $loans->count() }} dari {{ $loans->total() }} peminjaman
                    </p>
                    
                    <!-- Pagination -->
                    @if($loans->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{ $loans->links() }}
                            </ul>
                        </nav>
                    @endif
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-journal-x display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Tidak ada data peminjaman</h4>
                    <p class="text-muted mb-4">Belum ada anggota yang meminjam buku</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection