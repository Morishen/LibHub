@extends('layouts.app')

@section('title', 'Edit Profil Member')

@section('content')
<div class="container">
    <h2 class="mb-4">Pengaturan Profil</h2>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <span class="text-white fs-2">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Statistik Peminjaman</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="py-2 border-bottom">
                            <span class="me-2">📚</span> Dipinjam: <strong>{{ $activeLoansCount }}</strong>
                        </li>
                        <li class="py-2 border-bottom">
                            <span class="me-2">✅</span> Selesai: <strong>{{ $completedLoansCount }}</strong>
                        </li>
                        <li class="py-2">
                            <span class="me-2">⚠️</span> Terlambat: <strong>{{ $overdueLoansCount }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Update Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $user->phone) }}" placeholder="Masukkan nomor HP aktif">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection