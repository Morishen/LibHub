@extends('layouts.app')

@section('title', 'Dashboard Member')

@section('content')
<div class="container">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="card-title mb-1">
                                <i class="bi bi-person-circle me-2"></i>
                                Selamat datang, {{ auth()->user()->name }}!
                            </h3>
                            <p class="mb-0 opacity-75">
                                Terakhir login: {{ now()->format('d F Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('catalog.index') }}" class="btn btn-light">
                                <i class="bi bi-search me-1"></i>Cari Buku
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <!-- Profile Picture Placeholder -->
                    <div class="mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px;">
                            <span class="fs-1">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <h5 class="card-title">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-4">{{ auth()->user()->email }}</p>
                    
                    <!-- User Info -->
                    <div class="text-start">
                        @if(auth()->user()->phone)
                            <p class="mb-2">
                                <i class="bi bi-telephone me-2"></i>
                                {{ auth()->user()->phone }}
                            </p>
                        @endif
                        
                        @if(auth()->user()->address)
                            <p class="mb-0">
                                <i class="bi bi-geo-alt me-2"></i>
                                {{ Str::limit(auth()->user()->address, 50) }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-pencil-square me-1"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loan Statistics -->
        <div class="col-lg-8">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="bi bi-journal-check display-6"></i>
                            </div>
                            <h3 class="mb-1">{{ $activeLoansCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Dipinjam</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-success mb-2">
                                <i class="bi bi-check-circle display-6"></i>
                            </div>
                            <h3 class="mb-1">{{ $completedLoansCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Selesai</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-danger mb-2">
                                <i class="bi bi-exclamation-triangle display-6"></i>
                            </div>
                            <h3 class="mb-1">{{ $overdueLoansCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Terlambat</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Loans -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terakhir
                        </h5>
                        <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($loans) && $loans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Batas Kembali</th>
                                        <th>Status</th>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-journal-x display-1 text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">Belum ada riwayat peminjaman</h5>
                            <p class="text-muted mb-4">Mulai jelajahi katalog buku kami</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Jelajahi Katalog
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection