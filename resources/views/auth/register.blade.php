@extends('layouts.guest')

@section('title', 'Registrasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="card-title">Daftar Akun LibHub</h3>
                    <p class="text-muted">Buat akun baru untuk mulai meminjam buku</p>
                </div>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap *</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Nama lengkap" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="nama@email.com" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Minimal 8 karakter" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-phone"></i>
                                </span>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="0812-3456-7890">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-geo-alt"></i>
                                </span>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="1" 
                                          placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <small class="text-muted">* Menandakan field wajib diisi</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-person-plus me-2"></i>Daftar Akun
                    </button>
                </form>
                
                <hr class="my-4">
                
                <p class="text-center mb-0">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                        Login disini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection