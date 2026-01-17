@extends('layouts.app')

@section('title', 'Kelola Buku')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-book me-2"></i>Kelola Buku
            </h2>
            <p class="text-muted mb-0">Kelola koleksi buku perpustakaan</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Buku Baru
        </a>
    </div>
    
    <!-- Search & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.books.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Cari judul/pengarang/ISBN..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>
                            Tidak Tersedia
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Books Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($books->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <td>
                                        <code>{{ $book->isbn }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $book->title }}</strong>
                                    </td>
                                    <td>{{ $book->author }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $book->category->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $book->available_copies }} / {{ $book->total_copies }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($book->available_copies > 0)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('catalog.show', $book) }}" 
                                               class="btn btn-outline-info" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.books.edit', $book) }}" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.books.destroy', $book) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Results Count -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="text-muted mb-0">
                        Menampilkan {{ $books->count() }} dari {{ $books->total() }} buku
                    </p>
                    
                    <!-- Pagination -->
                    @if($books->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{ $books->links() }}
                            </ul>
                        </nav>
                    @endif
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-book display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Tidak ada buku ditemukan</h4>
                    <p class="text-muted mb-4">Mulai tambah buku pertama Anda</p>
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Buku Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection