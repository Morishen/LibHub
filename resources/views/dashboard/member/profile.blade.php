@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container">
    @if(auth()->user()->is_admin)
        <div class="alert alert-warning shadow-sm">
            <h4 class="alert-heading">Akses Dibatasi!</h4>
            <p>Halaman profil member ini hanya tersedia untuk akun Member saja. Sebagai Admin/Owner, Anda dapat mengelola data melalui Panel Admin.</p>
            <hr>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-warning">Kembali ke Panel Admin</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <span class="text-white fs-1">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h4 class="fw-bold">{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        <hr>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Edit Profil
                        </a>
                    </div>
                </div>

                <div class="card mt-4 shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Statistik Peminjaman</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pinjaman Aktif</span>
                            <span class="badge bg-info text-dark">{{ $activeLoansCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Selesai</span>
                            <span class="badge bg-success">{{ $completedLoansCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Terlambat</span>
                            <span class="badge bg-danger">{{ $overdueLoansCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Detail Informasi Akun</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Nama Lengkap</div>
                            <div class="col-sm-8 fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Alamat Email</div>
                            <div class="col-sm-8 fw-semibold">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Nomor Telepon</div>
                            <div class="col-sm-8 fw-semibold">{{ $user->phone ?? '-' }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Alamat Lengkap</div>
                            <div class="col-sm-8 fw-semibold">{{ $user->address ?? '-' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-muted">Bergabung Sejak</div>
                            <div class="col-sm-8 fw-semibold">{{ $user->created_at->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection