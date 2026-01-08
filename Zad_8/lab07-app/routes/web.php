<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard - dostępny dla zalogowanych użytkowników
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth','verified_email')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Panel admina - dostępny TYLKO dla administratorów
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.index');
    Route::get('/admin/users', [AdminController::class, 'listUsers'])
        ->name('admin.users');
    Route::post('/admin/users/{user}/toggle-admin',
        [AdminController::class, 'toggleAdmin'])
        ->name('admin.toggle-admin');
});

require __DIR__.'/auth.php';

