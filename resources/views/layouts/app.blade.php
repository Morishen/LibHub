<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LibHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        
        .book-status-available { color: #198754; }
        .book-status-borrowed { color: #dc3545; }
        
        main {
            min-height: calc(100vh - 56px);
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <i class="bi bi-book-half me-2"></i>LibHub
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('catalog.index') }}">
                                    <i class="bi bi-search me-1"></i>Katalog
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('loans.index') }}">
                                    <i class="bi bi-journal-text me-1"></i>Peminjaman
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="container-fluid">
        @if(auth()->check() && auth()->user()->is_admin)
            <div class="row">
                <!-- Admin Sidebar -->
                <div class="col-md-2 sidebar d-none d-md-block">
                    <div class="pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link {{ request()->routeIs('admin.books*') ? 'active' : '' }}" 
                                   href="{{ route('admin.books.index') }}">
                                    <i class="bi bi-book me-2"></i>Kelola Buku
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                                   href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-people me-2"></i>Kelola Anggota
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link {{ request()->routeIs('admin.loans*') ? 'active' : '' }}" 
                                   href="{{ route('admin.loans.index') }}">
                                    <i class="bi bi-journal-check me-2"></i>Kelola Peminjaman
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" 
                                   href="{{ route('admin.categories.index') }}">
                                    <i class="bi bi-tags me-2"></i>Kategori
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Main Content Area -->
                <div class="col-md-10 ms-auto">
                    @endif
                    
                    <main class="py-4">
                        <div class="container">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @yield('content')
                        </div>
                    </main>
                    
                    @if(auth()->check() && auth()->user()->is_admin)
                </div>
            </div>
        @endif
    </div>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-book-half me-2"></i>LibHub</h5>
                    <p class="mb-0">Sistem Manajemen Perpustakaan Digital</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} LibHub. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>