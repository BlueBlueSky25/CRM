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

Route::get('/menu', function () {
    return view('layout.menu');
})->name('menu');

Route::get('/role', function () {
    return view('layout.role');
})->name('role');

Route::get('/user', function () {
    return view('layout.user');
})->name('user');

