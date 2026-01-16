@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="card-title">Lupa Password</h3>
                    <p class="text-muted">
                        Masukkan email Anda. Kami akan mengirimkan link untuk reset password.
                    </p>
                </div>
                
                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="mb-4">
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
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-send me-2"></i>Kirim Link Reset Password
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection