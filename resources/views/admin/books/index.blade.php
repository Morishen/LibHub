@extends('layouts.app')

@section('title', 'Kelola Buku')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-book me-2"></i>Kelola Buku
            </h2>
            <p class="text-muted mb-0">Kelola koleksi buku perpustakaan</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Buku Baru
            </a>
            {{-- Tombol Import hanya muncul jika route-nya ada --}}
            @if(Route::has('admin.books.import'))
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i>Import
            </button>
            @endif
        </div>
    </div>

    @php
        $categories = $categories ?? collect();
        $stats = $stats ?? [];
        $totalItems = method_exists($books, 'total') ? $books->total() : ($books ? $books->count() : 0);
    @endphp
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.books.index') }}" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Cari judul/pengarang/ISBN..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select name="sort" class="form-select" id="sortSelect">
                        <option value="">Urutkan</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                        <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Pengarang</option>
                    </select>
                </div>
                
                <div class="col-md-3 text-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                        @if(Route::has('admin.books.export'))
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal" title="Export Data">
                            <i class="bi bi-download"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row mb-4 g-3">
        @php
            $widgetData = [
                ['Total Buku', $stats['total_books'] ?? $totalItems, 'primary', 'bi-book'],
                ['Tersedia', $stats['available_books'] ?? 0, 'success', 'bi-check-circle'],
                ['Dipinjam', $stats['borrowed_books'] ?? 0, 'warning', 'bi-clock'],
                ['Habis', $stats['unavailable_books'] ?? 0, 'danger', 'bi-x-circle'],
            ];
        @endphp
        @foreach($widgetData as $w)
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-{{ $w[2] }} bg-opacity-10 h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-{{ $w[2] }} small mb-1 fw-bold">{{ $w[0] }}</h6>
                        <h4 class="mb-0 fw-bold">{{ number_format($w[1]) }}</h4>
                    </div>
                    <div class="bg-{{ $w[2] }} text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi {{ $w[3] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            @if(isset($books) && $books->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th width="80">Cover</th>
                                <th>Informasi Buku</th>
                                <th>Kategori</th>
                                <th width="150">Stok (Tersedia)</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr class="{{ ($book->available_copies ?? 0) == 0 ? 'bg-light text-muted' : '' }}">
                                    <td class="text-center small text-muted">
                                        {{ method_exists($books, 'firstItem') ? ($books->firstItem() + $loop->index) : ($loop->iteration) }}
                                    </td>
                                    <td>
                                        <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : 'https://ui-avatars.com/api/?name='.urlencode($book->title).'&background=f8f9fa&color=6c757d' }}" 
                                             class="rounded shadow-sm border" 
                                             style="width: 45px; height: 65px; object-fit: cover; cursor: pointer;"
                                             data-bs-toggle="modal" data-bs-target="#imgModal{{ $book->id }}"
                                             alt="{{ $book->title }}">
                                    </td>
                                    <td>
                                        <div class="fw-bold mb-0 text-dark">{{ Str::limit($book->title, 60) }}</div>
                                        <div class="small text-muted">
                                            <span class="me-2"><i class="bi bi-person me-1"></i>{{ $book->author }}</span>
                                            <span class="badge bg-light text-dark border fw-normal">ISBN: {{ $book->isbn }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-25 fw-normal">
                                            {{ $book->category->name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php 
                                            $total = $book->total_copies ?? 0;
                                            $available = $book->available_copies ?? 0;
                                            $percent = $total > 0 ? ($available / $total) * 100 : 0; 
                                        @endphp
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar {{ $percent > 20 ? 'bg-success' : 'bg-danger' }}" role="progressbar" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="fw-bold {{ $available == 0 ? 'text-danger' : 'text-dark' }}">{{ $available }}</small>
                                            <small class="text-muted">/ {{ $total }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm border rounded shadow-sm">
                                            {{-- PERBAIKAN: Gunakan admin.books.show, bukan catalog.show --}}
                                            <a href="{{ route('admin.books.show', $book) }}" class="btn btn-white" title="Lihat Detail"><i class="bi bi-eye text-info"></i></a>
                                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-white" title="Edit Buku"><i class="bi bi-pencil text-primary"></i></a>
                                            <button type="button" class="btn btn-white" onclick="handleDelete({{ $book->id }}, '{{ addslashes($book->title) }}')" title="Hapus">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $book->id }}" action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                {{-- Modal Zoom Gambar Tetap Disini --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Tetap Disini --}}
            @else
                {{-- Tampilan Kosong Tetap Disini --}}
            @endif
        </div>
    </div>
</div>

{{-- Modal Import (Hanya muncul jika route ada) --}}
@if(Route::has('admin.books.import'))
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('admin.books.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-upload me-2"></i>Import Data Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="file" class="form-control" name="file" required accept=".xlsx,.xls,.csv">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Mulai Import</button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
    function handleDelete(id, title) {
        if (confirm(`Apakah Anda yakin ingin menghapus buku "${title}"?`)) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    }
</script>
@endpush
@endsection