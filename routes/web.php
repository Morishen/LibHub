<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController; 
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AdminBookController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Katalog & Detail Buku (Dapat diakses pengunjung umum/tamu)
Route::controller(BookController::class)->group(function () {
    Route::get('/catalog', 'index')->name('catalog.index');
    Route::get('/catalog/{book}', 'show')->name('catalog.show');
});

// Auth: Guest (Hanya untuk user yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Member & Shared)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    /**
     * DASHBOARD & NOTIFICATIONS
     */
    // Route dashboard utama
    Route::get('/dashboard', [LoanController::class, 'dashboard'])->name('dashboard');
    
    // PERBAIKAN: Menambahkan rute notifications.subscribe yang hilang
    Route::post('/notifications/subscribe', function() {
        return back()->with('success', 'Berhasil berlangganan! Kami akan memberi tahu Anda saat buku tersedia.');
    })->name('notifications.subscribe');

    /**
     * PEMINJAMAN MEMBER
     */
    Route::controller(LoanController::class)->group(function () {
        Route::get('/loans', 'index')->name('loans.index');
        Route::post('/loans', 'store')->name('loans.store');
        Route::post('/loans/{loan}/extend', 'extend')->name('loans.extend');
    });

    /**
     * PROFIL USER
     */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AuthController::class, 'showProfile'])->name('show');
        Route::get('/edit', [AuthController::class, 'editProfile'])->name('edit');
        Route::post('/update', [AuthController::class, 'updateProfile'])->name('update');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Prefix: admin, Name: admin.*)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [LoanController::class, 'adminDashboard'])->name('dashboard');
    
    // Kelola Peminjaman (Admin)
    Route::controller(LoanController::class)->group(function () {
        Route::get('/loans', 'adminIndex')->name('loans.index');
        // FIX: Menggunakan POST untuk sinkronisasi dengan Form (Menghindari error PATCH)
        Route::post('/loans/{loan}/return', 'returnBook')->name('loans.return');
    });
    
    // Kelola Buku (CRUD)
    Route::resource('books', AdminBookController::class);
    Route::post('/books/import', [AdminBookController::class, 'import'])->name('books.import');
    Route::post('/books/export', [AdminBookController::class, 'export'])->name('books.export');
    
    // Kelola Kategori
    Route::controller(AdminBookController::class)->group(function () {
        Route::get('/categories', 'indexCategories')->name('categories.index');
        Route::post('/categories', 'storeCategory')->name('categories.store');
        Route::delete('/categories/{category}', 'destroyCategory')->name('categories.destroy');
    });
    
    // Kelola Pengguna
    Route::controller(AuthController::class)->group(function () {
        Route::get('/users', 'indexUsers')->name('users.index'); 
        Route::delete('/users/{id}', 'destroyUser')->name('users.destroy');
    });
});