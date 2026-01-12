<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;

// Home
Route::get('/', [ShortUrlController::class, 'index'])->name('home');
Route::post('/shorten', [ShortUrlController::class, 'store'])->name('shorten');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Dashboard (requires auth + verified email)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/urls', [DashboardController::class, 'store'])->name('dashboard.urls.store');
    Route::get('/dashboard/urls/{id}', [DashboardController::class, 'show'])->name('dashboard.urls.show');
    Route::put('/dashboard/urls/{id}', [DashboardController::class, 'update'])->name('dashboard.urls.update');
    Route::delete('/dashboard/urls/{id}', [DashboardController::class, 'destroy'])->name('dashboard.urls.destroy');
});

// Short URL redirect (must be last)
Route::get('/{code}', [ShortUrlController::class, 'redirect'])->name('redirect')->where('code', '[A-Za-z0-9]{4,20}');
