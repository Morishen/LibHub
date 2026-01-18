<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
public function index(Request $request)
{
    $search   = $request->get('search');
    $category = $request->get('category');
    $sort     = $request->get('sort', 'latest');

    $query = Book::with('category');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%");
        });
    }

    if ($category) {
        $query->where('category_id', $category);
    }

    if ($sort === 'title') {
        $query->orderBy('title');
    } else {
        $query->latest();
    }

    $books = $query->paginate(12);
    $categories = Category::orderBy('name')->get();

    return view('catalog.index', compact('books', 'categories'));
}

    // Menampilkan detail buku
    public function show(Book $book)
    {
        $book->load('category', 'loans.user'); // jika relasi loans dan user sudah dibuat
        return view('catalog.show', compact('book'));
    }
}

