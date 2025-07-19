<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Middleware\EnsureUserIsActive;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Email Verification Routes (optional)
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('verification.resend');
});

// Protected Routes - Require authentication and active user
Route::middleware(['auth', EnsureUserIsActive::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes - Require invitation permissions
    Route::middleware(['can:invite-users'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('invitations', InvitationController::class)->except(['show', 'edit', 'update']);
    });

    // Book Routes - accessible to all authenticated users
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create')->middleware('can:manage-books');
    Route::post('/books', [BookController::class, 'store'])->name('books.store')->middleware('can:manage-books');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit')->middleware('can:manage-books');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update')->middleware('can:manage-books');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy')->middleware('can:manage-books');
    Route::get('/books/{book}/loan', [BookController::class, 'loan'])->name('books.loan');
    Route::post('/books/{book}/loan', [BookController::class, 'processLoan'])->name('books.process-loan');

    // Loan Routes - accessible to all authenticated users
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create')->middleware('can:manage-loans');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store')->middleware('can:manage-loans');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('/loans/{loan}/edit', [LoanController::class, 'edit'])->name('loans.edit')->middleware('can:manage-loans');
    Route::put('/loans/{loan}', [LoanController::class, 'update'])->name('loans.update')->middleware('can:manage-loans');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return')->middleware('can:manage-loans');
    Route::patch('/loans/{loan}/extend', [LoanController::class, 'extend'])->name('loans.extend')->middleware('can:manage-loans');

    // Claims - accessible to all authenticated users
    Route::resource('claims', ClaimController::class)->except(['edit', 'destroy']);

    // Claim management - only for admins and librarians
    Route::middleware(['can:manage-loans'])->group(function () {
        Route::patch('/claims/{claim}', [ClaimController::class, 'update'])->name('claims.update');
    });
});
