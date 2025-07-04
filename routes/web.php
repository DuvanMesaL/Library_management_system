<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Email Verification Routes
Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');

// Protected Routes
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('can:invite-users')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('invitations', InvitationController::class)->except(['show', 'edit', 'update']);
    });
});
