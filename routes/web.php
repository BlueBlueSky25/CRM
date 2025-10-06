<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CompanyChartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================
// Public Routes (Login / Logout)
// ==========================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================
// CASCADE DROPDOWN ROUTES - BUTUH AUTH
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/get-regencies/{provinceId}', [UserController::class, 'getRegencies']);
    Route::get('/get-districts/{regencyId}', [UserController::class, 'getDistricts']);
    Route::get('/get-villages/{districtId}', [UserController::class, 'getVillages']);
});

// ==========================
// Protected Routes (Harus Login & Permission)
// ==========================
Route::middleware(['auth', 'permission'])->group(function () {
    
    // ==========================
    // Dashboard
    // ==========================
    Route::get('/dashboard', [CompanyChartController::class, 'index'])
        ->name('dashboard');

    // ==========================
    // Static Pages (Blade)
    // ==========================
    Route::get('/customers', fn() => view('layout.customers'))->name('customers');
    Route::get('/industri', fn() => view('layout.industri'))->name('industri');

    // ==========================
    // Calendar (React Page khusus)
    // ==========================
    Route::get('/calendar/{any?}', function () {
        return view('react'); // react.blade.php
    })->where('any', '.*')->name('calendar');

    // ==========================
    // Search APIs (AJAX)
    // ==========================
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/roles/search', [RoleController::class, 'search'])->name('roles.search');
    Route::get('/menus/search', [MenuController::class, 'search'])->name('menus.search');

    // ==========================
    // Settings Pages
    // ==========================
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/role', [RoleController::class, 'index'])->name('role'); 
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    // ==========================
    // CRUD - Users
    // ==========================
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // ==========================
    // CRUD - Roles
    // ==========================
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store'); 
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update'); 
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy'); 
    Route::post('/roles/{id}/assign-menu', [RoleController::class, 'assignMenu'])->name('roles.assignMenu');

    // ==========================
    // CRUD - Menus
    // ==========================
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store'); 
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update'); 
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy'); 

    // ==========================
    // Marketing (Sales)
    // ==========================
    Route::get('/marketing', [SalesController::class, 'index'])->name('marketing');
    Route::post('/marketing/sales', [SalesController::class, 'store'])->name('marketing.sales.store');
    Route::put('/marketing/sales/{id}', [SalesController::class, 'update'])->name('marketing.sales.update');
    Route::delete('/marketing/sales/{id}', [SalesController::class, 'destroy'])->name('marketing.sales.destroy');

    // ==========================
    // Catch-all React (HARUS PALING BAWAH)
    // ==========================
    Route::get('/{any}', function () {
        return view('react');
    })->where('any', '.*');
});
