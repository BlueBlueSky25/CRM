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
Route::middleware(['auth', 'permission'])->group(function () {
    
    // ==========================
    // Dashboard & Pages
    // ==========================
    Route::get('/dashboard', function () {
        return view('layout.dashboard');
    })->name('dashboard');

    Route::get('/customers', function () {
        return view('layout.customers');
    })->name('customers');

    Route::get('/chart', function () {
        return view('layout.chart');
    })->name('chart');

    // ==========================
    // Settings Pages
    // ==========================
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/role', [RoleController::class, 'index'])->name('role'); 
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
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store'); 
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update'); 
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy'); 
    Route::post('/roles/{id}/assign-menu', [RoleController::class, 'assignMenu'])->name('roles.assignMenu');

    
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store'); 
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update'); 
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy'); 



});