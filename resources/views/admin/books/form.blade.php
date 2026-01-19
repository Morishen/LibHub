@extends('layouts.app')

@php
    $isEdit = isset($book) && $book->exists;
    $title = $isEdit ? 'Edit Buku' : 'Tambah Buku Baru';
@endphp

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">{{ $title }}</h2>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" 
                          action="{{ $isEdit ? route('admin.books.update', $book) : route('admin.books.store') }}" 
                          enctype="multipart/form-data">
                        @csrf
                        @if($isEdit) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold small">Judul Buku *</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title', $book->title ?? '') }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">ISBN *</label>
                                <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" 
                                       value="{{ old('isbn', $book->isbn ?? '') }}" required>
                                @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Pengarang *</label>
                                <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" 
                                       value="{{ old('author', $book->author ?? '') }}" required>
                                @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Kategori *</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('category_id', $book->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Penerbit</label>
                                <input type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror" 
                                       value="{{ old('publisher', $book->publisher ?? '') }}" placeholder="Contoh: Gramedia">
                                @error('publisher') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tahun Terbit</label>
                                <input type="number" name="publication_year" class="form-control @error('publication_year') is-invalid @enderror" 
                                       value="{{ old('publication_year', $book->publication_year ?? '') }}" placeholder="Contoh: 2024">
                                @error('publication_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Total Stok</label>
                                <input type="number" name="total_copies" class="form-control" value="{{ old('total_copies', $book->total_copies ?? 1) }}" min="1">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Stok Tersedia</label>
                                <input type="number" name="available_copies" class="form-control" value="{{ old('available_copies', $book->available_copies ?? 1) }}" min="0">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small">Cover Buku</label>
                                @if($isEdit && $book->cover_image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$book->cover_image) }}" class="img-thumbnail" style="height: 100px">
                                    </div>
                                @endif
                                <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                                @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small">Deskripsi</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $book->description ?? '') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-lg me-1"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Tambahkan Buku' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection