<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan untuk handle gambar

class BookController extends Controller
{
    public function index(Request $request)
    {
        // 1. Logika proteksi: Jika yang login Admin, lempar ke halaman index Admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.books.index');
        }

        // 2. Query untuk katalog member
        $query = Book::with('category');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%')
                  ->orWhere('publisher', 'like', '%' . $request->search . '%'); // Tambahkan pencarian penerbit
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('catalog.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load(['category']);
        
        $recentLoans = collect(); 
        if(Auth::check() && Auth::user()->isAdmin()) {
            if (class_exists('\App\Models\Loan')) {
                $recentLoans = \App\Models\Loan::with('user')
                                ->where('book_id', $book->id)
                                ->latest()
                                ->take(5)
                                ->get();
            }
        }

        return view('catalog.show', compact('book', 'recentLoans'));
    }

    // ============================================================
    // TAMBAHKAN FUNGSI DI BAWAH INI UNTUK HANDLE FORM ADMIN
    // ============================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'author' => 'required|string|max:100',
            'publisher' => 'nullable|string|max:100', // Sinkron database
            'publication_year' => 'nullable|integer|digits:4', // Sinkron database
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'required|integer|min:0|max:'.$request->total_copies,
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'isbn' => 'required|string|max:20|unique:books,isbn,'.$book->id,
            'author' => 'required|string|max:100',
            'publisher' => 'nullable|string|max:100', // Sinkron database
            'publication_year' => 'nullable|integer|digits:4', // Sinkron database
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus gambar lama jika ada
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }
}