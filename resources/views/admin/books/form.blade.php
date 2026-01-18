@extends('layouts.app')

@section('title', $book ? 'Edit Buku' : 'Tambah Buku Baru')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.books.index') }}" class="text-decoration-none">
                    <i class="bi bi-book me-1"></i>Kelola Buku
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $book ? 'Edit Buku' : 'Tambah Buku' }}
            </li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-journal-plus me-2"></i>
                        {{ $book ? 'Edit Buku' : 'Tambah Buku Baru' }}
                    </h4>
                    <p class="text-muted mb-0">
                        {{ $book ? 'Perbarui informasi buku' : 'Tambahkan buku baru ke katalog' }}
                    </p>
                </div>
                
                <div class="card-body">
                    <form method="POST" 
                          action="{{ $book ? route('admin.books.update', $book) : route('admin.books.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        @if($book)
                            @method('PUT')
                        @endif
                        
                        <!-- Book Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="isbn" class="form-label">ISBN *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                           id="isbn" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" 
                                           placeholder="Contoh: 978-602-8519-93-9" required>
                                </div>
                                @error('isbn')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Judul Buku *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-journal-text"></i>
                                    </span>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $book->title ?? '') }}" 
                                           placeholder="Judul lengkap buku" required>
                                </div>
                                @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Pengarang *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control @error('author') is-invalid @enderror" 
                                           id="author" name="author" value="{{ old('author', $book->author ?? '') }}" 
                                           placeholder="Nama pengarang" required>
                                </div>
                                @error('author')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="publisher" class="form-label">Penerbit</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <input type="text" class="form-control @error('publisher') is-invalid @enderror" 
                                           id="publisher" name="publisher" value="{{ old('publisher', $book->publisher ?? '') }}" 
                                           placeholder="Nama penerbit">
                                </div>
                                @error('publisher')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="publication_year" class="form-label">Tahun Terbit</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-calendar"></i>
                                    </span>
                                    <input type="number" class="form-control @error('publication_year') is-invalid @enderror" 
                                           id="publication_year" name="publication_year" 
                                           value="{{ old('publication_year', $book->publication_year ?? '') }}" 
                                           min="1900" max="{{ date('Y') }}" placeholder="YYYY">
                                </div>
                                @error('publication_year')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="form-label">Kategori *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-tag"></i>
                                    </span>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $book->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="total_copies" class="form-label">Jumlah Kopi *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-stack"></i>
                                    </span>
                                    <input type="number" class="form-control @error('total_copies') is-invalid @enderror" 
                                           id="total_copies" name="total_copies" 
                                           value="{{ old('total_copies', $book->total_copies ?? 1) }}" min="1" required>
                                </div>
                                @error('total_copies')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Jumlah kopi fisik yang tersedia</small>
                            </div>
                        </div>

                        <!-- ADDED: Available copies -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="available_copies" class="form-label">Kopi Tersedia *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-check2-circle"></i>
                                    </span>
                                    <input type="number" class="form-control @error('available_copies') is-invalid @enderror"
                                           id="available_copies" name="available_copies"
                                           value="{{ old('available_copies', $book->available_copies ?? ($book->total_copies ?? 1)) }}"
                                           min="0" required>
                                </div>
                                @error('available_copies')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kopi yang bisa dipinjam saat ini</small>
                            </div>
                        </div>
                        <!-- END ADDED -->
                        
                        <!-- Book Cover Image -->
                        <div class="mb-4">
                            <label for="cover_image" class="form-label">Cover Buku</label>
                            
                            <!-- Preview existing image -->
                            @if($book && $book->cover_image)
                                <div class="card mb-3 border" style="max-width: 300px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <img src="{{ $book->cover_image_url }}" 
                                                 alt="Current cover" 
                                                 style="max-width: 80px; max-height: 100px; object-fit: cover;"
                                                 class="img-thumbnail me-3">
                                            <div class="flex-grow-1">
                                                <p class="mb-1"><strong>Cover saat ini:</strong></p>
                                                <p class="text-muted small mb-2">
                                                    {{ $book->title }}
                                                </p>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="remove_image" id="remove_image" value="1">
                                                    <label class="form-check-label text-danger small" for="remove_image">
                                                        Hapus gambar ini
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($book)
                                <div class="alert alert-info mb-3 py-2" style="max-width: 300px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Tidak ada gambar cover
                                </div>
                            @endif
                            
                            <!-- File Upload -->
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-image"></i>
                                </span>
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                       id="cover_image" name="cover_image"
                                       accept="image/jpeg,image/png,image/gif">
                            </div>
                            
                            <div class="form-text">
                                Upload gambar cover buku (JPEG, PNG, GIF, maksimal 5MB).
                                @if($book)
                                    Kosongkan jika tidak ingin mengubah gambar.
                                @endif
                            </div>
                            
                            @error('cover_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Deskripsi Buku</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Deskripsi singkat tentang buku">{{ old('description', $book->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ $book ? 'Update Buku' : 'Simpan Buku' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Image Preview Script -->
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const coverImageInput = document.getElementById('cover_image');
                    const previewContainer = document.querySelector('.card.mb-3.border');
                    
                    if (coverImageInput) {
                        coverImageInput.addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                
                                reader.onload = function(e) {
                                    // Remove existing preview if any
                                    let existingPreview = document.getElementById('imagePreview');
                                    if (existingPreview) {
                                        existingPreview.remove();
                                    }
                                    
                                    // Remove the "no image" alert if exists
                                    const noImageAlert = document.querySelector('.alert.alert-info');
                                    if (noImageAlert) {
                                        noImageAlert.remove();
                                    }
                                    
                                    // Create preview container if doesn't exist
                                    if (!previewContainer) {
                                        const previewDiv = document.createElement('div');
                                        previewDiv.className = 'card mb-3 border';
                                        previewDiv.style.maxWidth = '300px';
                                        previewDiv.id = 'imagePreview';
                                        
                                        const cardBody = document.createElement('div');
                                        cardBody.className = 'card-body p-3';
                                        
                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.alt = 'Preview';
                                        img.style.maxWidth = '80px';
                                        img.style.maxHeight = '100px';
                                        img.style.objectFit = 'cover';
                                        img.className = 'img-thumbnail me-3';
                                        
                                        const textDiv = document.createElement('div');
                                        textDiv.className = 'flex-grow-1';
                                        
                                        const title = document.createElement('p');
                                        title.className = 'mb-1';
                                        title.innerHTML = '<strong>Preview:</strong>';
                                        
                                        const fileName = document.createElement('p');
                                        fileName.className = 'text-muted small mb-0';
                                        fileName.textContent = file.name;
                                        
                                        textDiv.appendChild(title);
                                        textDiv.appendChild(fileName);
                                        
                                        cardBody.appendChild(img);
                                        cardBody.appendChild(textDiv);
                                        previewDiv.appendChild(cardBody);
                                        
                                        // Insert before the file input
                                        coverImageInput.parentNode.parentNode.parentNode.insertBefore(
                                            previewDiv, 
                                            coverImageInput.parentNode.parentNode
                                        );
                                    } else {
                                        // Update existing preview
                                        const img = previewContainer.querySelector('img');
                                        if (img) {
                                            img.src = e.target.result;
                                        }
                                        
                                        const fileName = previewContainer.querySelector('.text-muted.small');
                                        if (fileName) {
                                            fileName.textContent = file.name;
                                        }
                                        
                                        // Update the title
                                        const title = previewContainer.querySelector('strong');
                                        if (title) {
                                            title.textContent = 'Preview:';
                                        }
                                    }
                                }
                                
                                reader.readAsDataURL(file);
                            }
                        });
                    }
                    
                    // Handle remove image checkbox
                    const removeCheckbox = document.getElementById('remove_image');
                    if (removeCheckbox) {
                        removeCheckbox.addEventListener('change', function() {
                            const fileInput = document.getElementById('cover_image');
                            if (this.checked) {
                                if (fileInput) {
                                    fileInput.disabled = true;
                                }
                            } else {
                                if (fileInput) {
                                    fileInput.disabled = false;
                                }
                            }
                        });
                    }
                });
            </script>
            @endpush
        </div>
    </div>
</div>
@endsection