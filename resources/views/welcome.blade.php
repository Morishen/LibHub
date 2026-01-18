<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibHub - Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a2530 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,0V100H1000V0Z" fill="white"/></svg>');
            background-position: bottom;
            background-repeat: no-repeat;
            background-size: 100% 50px;
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 30px 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .book-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
        }
        
        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .book-cover {
            height: 200px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            padding: 80px 0;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
        }
        
        footer {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-book-half me-2"></i>
                <span class="text-primary fw-bold">Lib</span><span class="text-secondary">Hub</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#books">Koleksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    @guest
                        <li class="nav-item ms-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Masuk ke Dashboard</a>
                        </li>
                        <!-- Tambahan: tombol logout (POST) -->
                        <li class="nav-item ms-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    Logout
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container pt-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Selamat Datang di <span class="text-warning">LibHub</span>
                    </h1>
                    <p class="lead mb-4">
                        Perpustakaan digital modern dengan ribuan koleksi buku dari berbagai genre. 
                        Akses buku favorit Anda kapan saja, di mana saja.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">
                                <i class="bi bi-person-plus me-2"></i>Daftar Gratis
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                        @else
                            <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg px-4">
                                <i class="bi bi-book me-2"></i>Jelajahi Katalog
                            </a>
                            <a href="{{ route('member.dashboard') }}" class="btn btn-outline-light btn-lg px-4">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard Saya
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="book-cover rounded-3 mx-auto" style="width: 300px; height: 400px;">
                            <div class="text-center text-muted">
                                <i class="bi bi-book" style="font-size: 100px;"></i>
                                <p class="mt-3 fw-bold">Digital Library</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Mengapa Memilih LibHub?</h2>
                <p class="text-muted">Kami menyediakan layanan terbaik untuk kebutuhan membaca Anda</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="bi bi-collection"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Koleksi Lengkap</h4>
                        <p class="text-muted">
                            Ribuan buku dari berbagai kategori: fiksi, non-fiksi, akademik, dan masih banyak lagi.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Akses 24/7</h4>
                        <p class="text-muted">
                            Akses katalog dan pinjam buku kapan saja. Sistem kami tersedia 24 jam sehari.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Pencarian Cerdas</h4>
                        <p class="text-muted">
                            Temukan buku yang Anda cari dengan cepat menggunakan sistem pencarian yang canggih.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="stats" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">LibHub dalam Angka</h2>
                <p class="text-muted">Statistik yang terus berkembang</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-number text-primary">
                            {{ $stats['books'] ?? '5,000' }}+
                        </div>
                        <p class="text-muted mb-0">Judul Buku</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-number text-success">
                            {{ $stats['members'] ?? '2,500' }}+
                        </div>
                        <p class="text-muted mb-0">Anggota Aktif</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-number text-warning">
                            {{ $stats['loans'] ?? '15,000' }}+
                        </div>
                        <p class="text-muted mb-0">Buku Dipinjam</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-number text-info">
                            {{ $stats['categories'] ?? '50' }}+
                        </div>
                        <p class="text-muted mb-0">Kategori</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    <section id="books" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Buku Populer</h2>
                <p class="text-muted">Koleksi buku yang paling sering dipinjam</p>
            </div>
            
            <div class="row g-4">
                @php
                    $featuredBooks = [
                        ['title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'category' => 'Fiksi'],
                        ['title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'category' => 'Sejarah'],
                        ['title' => 'Filosofi Teras', 'author' => 'Henry Manampiring', 'category' => 'Filsafat'],
                        ['title' => 'Atomic Habits', 'author' => 'James Clear', 'category' => 'Pengembangan Diri'],
                    ];
                @endphp
                
                @foreach($featuredBooks as $book)
                    <div class="col-md-3">
                        <div class="book-card shadow-sm">
                            <div class="book-cover">
                                <i class="bi bi-journal-bookmark text-muted"></i>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $book['title'] }}</h6>
                                <p class="card-text text-muted small mb-2">
                                    {{ $book['author'] }}
                                </p>
                                <span class="badge bg-secondary">{{ $book['category'] }}</span>
                                <div class="mt-3">
                                    <span class="text-success">
                                        <i class="bi bi-check-circle"></i> Tersedia
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary w-100">
                                        Login untuk Pinjam
                                    </a>
                                @else
                                    <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-primary w-100">
                                        Lihat Detail
                                    </a>
                                @endguest
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-grid me-2"></i>Lihat Semua Buku
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold">Bergabunglah dengan Komunitas Pembaca Kami</h2>
                    <p class="lead mb-0">
                        Dapatkan akses ke ribuan buku digital dan fasilitas perpustakaan modern.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
                            Daftar Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @else
                        <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg px-5">
                            Mulai Membaca <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="fw-bold mb-3">
                        <i class="bi bi-book-half me-2"></i>
                        <span class="text-warning">Lib</span><span class="text-white">Hub</span>
                    </h4>
                    <p class="text-light">
                        Perpustakaan digital modern yang memberikan kemudahan akses 
                        kepada seluruh anggota untuk menikmati koleksi buku berkualitas.
                    </p>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#features" class="text-light text-decoration-none">Fitur</a></li>
                        <li class="mb-2"><a href="#stats" class="text-light text-decoration-none">Statistik</a></li>
                        <li class="mb-2"><a href="#books" class="text-light text-decoration-none">Koleksi</a></li>
                        <li><a href="#about" class="text-light text-decoration-none">Tentang</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Peminjaman Buku</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Katalog Online</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Rekomendasi Buku</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Bantuan</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 mb-4">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <ul class="list-unstyled text-light">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            Jl. Perpustakaan No. 123, Jakarta
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            info@libhub.id
                        </li>
                        <li>
                            <i class="bi bi-phone me-2"></i>
                            (021) 1234-5678
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-light my-4">
            
            <div class="text-center">
                <p class="mb-0 text-light">
                    &copy; {{ date('Y') }} LibHub. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>