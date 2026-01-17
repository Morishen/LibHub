@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="card-title">Login ke LibHub</h3>
                    <p class="text-muted">Masuk ke akun Anda untuk melanjutkan</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
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
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Masukkan password" required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        Lupa password?
                    </a>
                </div>
                
                <hr class="my-4">
                
                <p class="text-center mb-0">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                        Daftar disini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection