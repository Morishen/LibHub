@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold">Ringkasan Perpustakaan</h2>
        <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card bg-primary text-white border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Buku</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalBooksCount }}</h2>
                        </div>
                        <i class="bi bi-book fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Pinjaman Aktif</h6>
                            <h2 class="mb-0 fw-bold">{{ $activeLoansCount }}</h2>
                        </div>
                        <i class="bi bi-journal-check fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Terlambat</h6>
                            <h2 class="mb-0 fw-bold">{{ $overdueLoansCount }}</h2>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Anggota</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalUsersCount }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Peminjaman Terbaru</h5>
            <span class="badge bg-primary">Total: {{ $loans->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $loan->user->name }}</div>
                                <small class="text-muted">{{ $loan->user->email }}</small>
                            </td>
                            <td>{{ $loan->book->title }}</td>
                            <td>{{ $loan->borrow_date->format('d M Y') }}</td>
                            <td>
                                @if($loan->returned_at)
                                    <span class="badge bg-success">Dikembalikan</span>
                                @elseif($loan->due_date < now())
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-warning text-dark">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada transaksi terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Transaksi</a>
        </div>
    </div>
</div>
@endsection