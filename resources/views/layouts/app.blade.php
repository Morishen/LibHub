<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LibHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 250px;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f4f7f6;
        }

        .navbar {
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            padding-top: 1.5rem;
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 0.8rem 1.5rem;
            border-radius: 0;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .sidebar .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            border-right: 4px solid #0d6efd;
            font-weight: 600;
        }

        main {
            flex: 1;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        footer {
            z-index: 1030;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">
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
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('catalog.index') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
                                    <i class="bi bi-search me-1"></i>Katalog
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}" href="{{ route('loans.index') }}">
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
                            <a class="nav-link btn btn-light text-primary ms-lg-2 px-3" href="{{ route('register') }}">
                                Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                @if(!auth()->user()->is_admin)
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                            <i class="bi bi-person me-2"></i>Profil Saya
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
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
    
    <div class="container-fluid p-0">
        <div class="row g-0">
            @if(auth()->check() && auth()->user()->is_admin)
                <div class="col-md-3 col-lg-2 sidebar d-none d-md-block sticky-top" style="top: 56px; height: calc(100vh - 56px);">
                    <div class="d-flex flex-column h-100">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.books.index') || request()->routeIs('admin.books.create') || request()->routeIs('admin.books.edit') ? 'active' : '' }}" href="{{ route('admin.books.index') }}">
                                    <i class="bi bi-book me-2"></i>Kelola Buku
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-people me-2"></i>Kelola Anggota
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.loans*') ? 'active' : '' }}" href="{{ route('admin.loans.index') }}">
                                    <i class="bi bi-journal-check me-2"></i>Kelola Peminjaman
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                    <i class="bi bi-tags me-2"></i>Kategori
                                </a>
                            </li>
                        </ul>
                        
                        <div class="mt-auto p-3 border-top">
                            <a href="{{ route('catalog.index') }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-eye me-1"></i>Lihat Katalog
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9 col-lg-10">
            @else
                <div class="col-12">
            @endif
                    
                    <main class="py-4">
                        <div class="container">
                            {{-- Global Alert System --}}
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('info'))
                                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @yield('content')
                        </div>
                    </main>
                </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h5 class="fw-bold mb-1"><i class="bi bi-book-half me-2"></i>LibHub</h5>
                    <p class="text-secondary small mb-0">Sistem Manajemen Perpustakaan Digital Modern</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-secondary small mb-0">&copy; {{ date('Y') }} LibHub. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>