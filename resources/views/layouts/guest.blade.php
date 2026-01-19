<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LibHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        
        .auth-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .auth-logo {
            text-decoration: none;
            color: #2c3e50;
        }
        
        .auth-logo:hover {
            color: #3498db;
        }
    </style>
</head>
<body>
    <!-- Simple Navbar for Auth Pages -->
    <nav class="navbar navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand auth-logo" href="{{ route('welcome') }}">
                <i class="bi bi-book-half me-2"></i>LibHub
            </a>
            <div>
                <a href="{{ route('welcome') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </nav>
    
    <main class="container py-4">
        @yield('content')
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>