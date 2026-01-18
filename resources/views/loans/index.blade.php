@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman
            </h2>
            <p class="text-muted mb-0">Lihat semua riwayat peminjaman Anda</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-plus-circle me-1"></i>Pinjam Buku Lagi
        </a>
    </div>
    
    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('loans.index') }}" class="row g-3">
                <div class="col-md-4">
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
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari judul buku..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
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
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-journal text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <strong>{{ $loan->book->title }}</strong><br>
                                                <small class="text-muted">{{ $loan->book->author }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $loan->due_date->format('d/m/Y') }}
                                        @if($loan->is_overdue)
                                            <br><small class="text-danger">Terlambat</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($loan->returned_at)
                                            {{ $loan->returned_at->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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
                                    <td>
                                        @if(!$loan->returned_at)
                                            @if(!$loan->is_overdue)
                                                <form action="{{ route('loans.extend', $loan) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                            onclick="return confirm('Perpanjang peminjaman 7 hari?')">
                                                        <i class="bi bi-calendar-plus me-1"></i>Perpanjang
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($loans->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $loans->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-journal-x display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Belum ada riwayat peminjaman</h4>
                    <p class="text-muted mb-4">Mulai jelajahi katalog buku kami</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Jelajahi Katalog
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection