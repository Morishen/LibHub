<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AdminBookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/catalog', [BookController::class, 'index'])->name('catalog.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Rute Lupa Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    // PERBAIKAN: Rute POST ini harus di luar middleware 'auth' karena guest perlu akses ini
    Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Semua User yang Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Dashboard Utama (Gunakan nama 'dashboard' secara konsisten)
    Route::get('/dashboard', [LoanController::class, 'index'])->name('dashboard');
    
    // ALIAS: Menambahkan alias agar pemanggilan route('member.dashboard') di Blade tidak error
    Route::get('/dashboard/member', [LoanController::class, 'index'])->name('member.dashboard');

    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans/store', [LoanController::class, 'store'])->name('loans.store');

    // PERBAIKAN: Gunakan Controller untuk Profile Edit agar variabel $activeLoansCount terisi
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Khusus Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // ALIAS: Menambahkan rute agar route('admin.dashboard') di layout admin tidak error
    Route::get('/dashboard', [LoanController::class, 'index'])->name('dashboard');
    
    Route::resource('books', AdminBookController::class);
    Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
});