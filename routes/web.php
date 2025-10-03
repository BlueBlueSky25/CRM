<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CompanyChartController;

// ==========================
// Public Routes
// ==========================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// ==========================
// CASCADE DROPDOWN ROUTES - HANYA BUTUH AUTH, TANPA PERMISSION
// ==========================
Route::middleware('auth')->group(function () {
Route::get('/get-regencies/{provinceId}', [UserController::class, 'getRegencies']);
Route::get('/get-districts/{regencyId}', [UserController::class, 'getDistricts']);
Route::get('/get-villages/{districtId}', [UserController::class, 'getVillages']);
});




// ==========================
// Protected Routes (Harus Login)
// ==========================
Route::middleware(['auth', 'permission'])->group(function () {
    
    // ==========================
    // Dashboard & Pages
    // ==========================
    Route::get('/dashboard', [CompanyChartController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth','permission']);

    Route::get('/customers', function () {
        return view('layout.customers');
    })->name('customers');

    // Route::get('/marketing', function () {
    //     return view('layout.marketing');
    // })->name('marketing');

    Route::get('/industri', function () {
        return view('layout.industri');
    })->name('industri');



    // ==========================
    // TAMBAHAN: 
    // ==========================
    
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');



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

    // ==========================
    // CRUD Operations - MENU
    // ==========================
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store'); 
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update'); 
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy'); 


    Route::get('/marketing', [SalesController::class, 'index'])->name('marketing');
    Route::post('/marketing/sales', [SalesController::class, 'store'])->name('marketing.sales.store');
    Route::put('/marketing/sales/{id}', [SalesController::class, 'update'])->name('marketing.sales.update');
    Route::delete('/marketing/sales/{id}', [SalesController::class, 'destroy'])->name('marketing.sales.destroy');




});