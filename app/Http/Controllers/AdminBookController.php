<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    /**
     * Menampilkan daftar semua buku (untuk admin).
     */
    public function index()
    {
        // Mengurutkan dari yang terbaru agar admin mudah melihat buku baru
        $books = Book::with('category')->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Menampilkan form tambah buku.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        // Menggunakan view admin.books.form sesuai kode awal Anda
        return view('admin.books.form', [
            'book' => new Book(),
            'categories' => $categories
        ]);
    }

    /**
     * Simpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn'             => 'required|string|max:20|unique:books,isbn',
            'title'            => 'required|string|max:200',
            'author'           => 'required|string|max:100',
            'publisher'        => 'nullable|string|max:100',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description'      => 'nullable|string',
            'cover_image'      => 'nullable|image|max:2048',
            'category_id'      => 'required|exists:categories,id',
            'total_copies'     => 'required|integer|min:1',
        ]);

        // LOGIKA TAMBAHAN: Otomatis set available_copies sama dengan total_copies saat buku baru dibuat
        $validated['available_copies'] = $request->total_copies;

        // Upload cover jika ada (Disarankan simpan path-nya, bukan filenya langsung di DB)
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.form', compact('book', 'categories'));
    }

    /**
     * Update data buku.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'isbn'             => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'title'            => 'required|string|max:200',
            'author'           => 'required|string|max:100',
            'publisher'        => 'nullable|string|max:100',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description'      => 'nullable|string',
            'cover_image'      => 'nullable|image|max:2048',
            'category_id'      => 'required|exists:categories,id',
            'total_copies'     => 'required|integer|min:1',
        ]);

        $diff = $request->total_copies - $book->total_copies;
        $validated['available_copies'] = max(0, $book->available_copies + $diff);

        if ($request->hasFile('cover_image')) {
            // Hapus foto lama jika ada
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku.
     */
    public function destroy(Book $book)
    {
        // Hapus file gambar dari storage sebelum data dihapus
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }
}