<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * PERBAIKAN: Nama class sesuai dengan nama file.
 * Logika dipisahkan agar Admin tidak tumpang tindih dengan rute Member.
 */
class AdminBookController extends Controller
{
    /**
     * Menampilkan daftar semua buku untuk Admin (Halaman Kelola Buku).
     */
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();
        
        // PERBAIKAN: Mengarah ke view index admin, bukan form/catalog
        return view('admin.books.index', [
            'books' => $books,
            'categories' => $categories,
            'book' => new Book() 
        ]);
    }

    /**
     * Menampilkan form tambah buku.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
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

        $validated['available_copies'] = $request->total_copies;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * PERBAIKAN: Menangani rute show agar tidak error.
     * Admin akan diarahkan ke edit atau jika ingin melihat detail bisa ke catalog.show.
     */
    public function show(Book $book)
    {
        // Secara default diarahkan ke edit karena Admin biasanya masuk ke sini untuk mengelola
        return redirect()->route('admin.books.edit', $book);
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

        // Hitung selisih stok agar ketersediaan buku sinkron
        $diff = $request->total_copies - $book->total_copies;
        $validated['available_copies'] = max(0, $book->available_copies + $diff);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku.
     */
    public function destroy(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | FITUR KATEGORI
    |--------------------------------------------------------------------------
    */

    public function indexCategories()
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        return view('admin.books.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);

        if ($category->books()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh buku.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}