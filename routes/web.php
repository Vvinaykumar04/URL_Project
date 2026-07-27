<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::redirect('/', '/login');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');
    Route::get('/short-urls/export', [ShortUrlController::class, 'export'])->name('short-urls.export');

    Route::middleware('role:SuperAdmin,Admin')->group(function () {
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.send');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/invitations/{token}', [InvitationController::class, 'acceptForm'])
        ->where('token', '[A-Za-z0-9]{48}')
        ->name('invitations.accept');
    Route::post('/invitations/{token}', [InvitationController::class, 'accept'])
        ->where('token', '[A-Za-z0-9]{48}')
        ->name('invitations.store');
});

Route::get('/s/{shortUrl:slug}', [ShortUrlController::class, 'show'])->name('short-urls.show');
