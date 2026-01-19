@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">
                <i class="bi bi-person-check-fill me-2 text-primary"></i>Kelola Peminjaman
            </h2>
            <p class="text-muted mb-0">Pantau dan kelola seluruh transaksi peminjaman buku perpustakaan.</p>
        </div>
        <div class="badge bg-primary px-3 py-2 shadow-sm rounded-pill">
            Total Transaksi: {{ $loans->total() ?? 0 }}
        </div>
    </div>
    
    {{-- Filter & Search Card --}}
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.loans.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                    <select name="status" class="form-select border-0 bg-light shadow-none">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif (Dipinjam)</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Selesai (Dikembalikan)</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label small fw-bold text-muted text-uppercase">Cari Peminjam/Buku</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-light shadow-none" 
                               placeholder="Masukkan nama peminjam atau judul buku..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100 shadow-sm">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-dark" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            @if(isset($loans) && $loans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Peminjam</th>
                                <th>Informasi Buku</th>
                                <th>Durasi Pinjam</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $loan->user->name ?? 'User Terhapus' }}</div>
                                        <small class="text-muted">{{ $loan->user->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded me-3 text-primary">
                                                <i class="bi bi-book"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark small">{{ $loan->book->title ?? 'Buku Tidak Ditemukan' }}</div>
                                                <small class="text-muted text-xs">ID: #{{ $loan->book_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <span class="text-muted">Pinjam:</span> {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}<br>
                                            <span class="text-muted">Batas:</span> 
                                            <span class="{{ ($loan->is_overdue && !$loan->returned_at) ? 'text-danger fw-bold' : '' }}">
                                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($loan->returned_at)
                                            <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check2-all me-1"></i>Selesai
                                            </span>
                                        @elseif($loan->is_overdue)
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-exclamation-octagon me-1"></i>Terlambat
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                <i class="bi bi-hourglass-split me-1"></i>Dipinjam
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        @if(!$loan->returned_at)
                                            {{-- FORM PERBAIKAN: Menggunakan method POST untuk sinkronisasi dengan route --}}
                                            <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST">
                                                @csrf
                                                {{-- JANGAN tambahkan @method('PATCH') di sini jika di web.php menggunakan Route::post --}}
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" 
                                                        onclick="return confirm('Apakah Anda yakin buku ini sudah dikembalikan?')">
                                                    <i class="bi bi-arrow-return-left me-1"></i>Tandai Kembali
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-muted small">
                                                <i class="bi bi-clock-history me-1"></i>
                                                Dikembalikan pada:<br>
                                                <strong>{{ \Carbon\Carbon::parse($loan->returned_at)->format('d/m/Y') }}</strong>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Section --}}
                <div class="p-4 border-top bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan <strong>{{ $loans->firstItem() }}</strong> - <strong>{{ $loans->lastItem() }}</strong> dari {{ $loans->total() }} data
                    </small>
                    <div>
                        {{ $loans->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-search text-muted opacity-25" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted fw-normal">Tidak ada data peminjaman ditemukan</h5>
                    <p class="text-muted small mb-3">Coba sesuaikan kata kunci atau filter status Anda.</p>
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-primary rounded-pill px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Tampilkan Semua Data
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .table thead th { 
        font-size: 0.7rem; 
        text-transform: uppercase; 
        letter-spacing: 1px;
        color: #8898aa;
        border-top: none;
    }
    .text-xs { font-size: 0.75rem; }
    .pagination { margin-bottom: 0; }
    /* Menghilangkan border default bootstrap yang mengganggu estetika light mode */
    .form-select:focus, .form-control:focus {
        border-color: #dee2e6;
        box-shadow: none;
    }
</style>
@endsection