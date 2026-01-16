@extends('layouts.app')

@section('title', 'Dashboard Member')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Member</h2>
    
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <span class="text-white fs-2">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <h5>{{ auth()->user()->name }}</h5>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    
                    @if(auth()->user()->phone)
                        <p class="mb-1">
                            <small>📱 {{ auth()->user()->phone }}</small>
                        </p>
                    @endif
                    
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm mt-2">
                        Edit Profil
                    </a>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Statistik Peminjaman</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="py-2 border-bottom">
                            <span class="me-2">📚</span>
                            Dipinjam: <strong>{{ $activeLoansCount }}</strong>
                        </li>
                        <li class="py-2 border-bottom">
                            <span class="me-2">✅</span>
                            Selesai: <strong>{{ $completedLoansCount }}</strong>
                        </li>
                        <li class="py-2">
                            <span class="me-2">⚠️</span>
                            Terlambat: <strong>{{ $overdueLoansCount }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Loan History -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Peminjaman</h5>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($loans->count() > 0)
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
                                            <td>{{ $loan->book->title }}</td>
                                            <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                            <td>
                                                @if($loan->returned_at)
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @elseif($loan->is_overdue)
                                                    <span class="badge bg-danger">Terlambat</span>
                                                @else
                                                    <span class="badge bg-warning">Dipinjam</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-3">Belum ada riwayat peminjaman</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                                Jelajahi Katalog
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection