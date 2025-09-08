<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ==========================
// Public Routes
// ==========================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================
// Dashboard & Pages
// ==========================
Route::get('/dashboard', function () {
    return view('layout.dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/customers', function () {
    return view('layout.customers');
})->name('customers');

