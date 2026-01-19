@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Peminjaman
            </h2>
            <p class="text-muted mb-0">Lihat semua riwayat peminjaman Anda</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i>Pinjam Buku Lagi
        </a>
    </div>
    
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('loans.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="small text-muted mb-1">Status</label>
                    <select name="status" class="form-select border-start-0">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted mb-1">Cari Buku</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" 
                               placeholder="Cari judul buku..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            @if($loans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th class="pe-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light p-2 rounded me-3">
                                                <i class="bi bi-book text-primary fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $loan->book->title }}</div>
                                                <small class="text-muted">{{ $loan->book->author }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Menggunakan optional() atau format standar untuk menghindari error jika kolom bukan Carbon instance --}}
                                    <td>{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="{{ $loan->is_overdue && !$loan->returned_at ? 'text-danger fw-bold' : '' }}">
                                            {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($loan->returned_at)
                                            {{ \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') }}
                                        @else
                                            <span class="text-muted small">Belum Kembali</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($loan->returned_at)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                <i class="bi bi-check2-circle me-1"></i>Selesai
                                            </span>
                                        @elseif($loan->is_overdue)
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                                <i class="bi bi-exclamation-octagon me-1"></i>Terlambat
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                                <i class="bi bi-hourglass-split me-1"></i>Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-center">
                                        {{-- Fitur Perpanjang hanya muncul jika belum kembali dan belum terlambat --}}
                                        @if(!$loan->returned_at && !$loan->is_overdue)
                                            <form action="{{ route('loans.extend', $loan) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3" 
                                                        onclick="return confirm('Perpanjang peminjaman buku ini selama 7 hari?')">
                                                    <i class="bi bi-calendar-plus me-1"></i>Perpanjang
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($loans->hasPages())
                    <div class="d-flex justify-content-center p-4">
                        {{ $loans->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/gray/book.svg" alt="No data" style="width: 200px;" class="mb-4 opacity-50">
                    <h4 class="text-muted fw-bold">Belum ada riwayat</h4>
                    <p class="text-muted mb-4 px-5">Anda belum meminjam buku apapun atau filter tidak ditemukan.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-search me-1"></i>Mulai Pinjam Buku
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection