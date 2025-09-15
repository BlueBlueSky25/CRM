<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\MenuController;

// ==========================
// Public Routes
// ==========================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================
// Protected Routes (Harus Login)
// ==========================
Route::middleware('auth')->group(function () {
    
    // ==========================
    // Dashboard & Pages
    // ==========================
    Route::get('/dashboard', function () {
    return view('layout.dashboard');
})->name('dashboard')->middleware(['auth', 'permission']);

Route::get('/customers', function () {
    return view('layout.customers');
})->name('customers')->middleware(['auth', 'permission']);

    // ==========================
    // Settings Pages
    // ==========================
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/role', [RoleController::class, 'index'])->name('role'); // <- TAMBAHKAN INI
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    // ==========================
    // CRUD Operations - USER
    // ==========================
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // ==========================
    // CRUD Operations - ROLE
    // ==========================
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store'); // <- TAMBAHKAN INI
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update'); // <- TAMBAHKAN INI
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy'); // <- TAMBAHKAN INIS
    Route::post('/roles/{id}/assign-menu', [RoleController::class, 'assignMenu'])->name('roles.assignMenu');

    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store'); // <- TAMBAHKAN INI
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update'); // <- TAMBAHKAN INI
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy'); // <- TAMBAHKAN INI

});