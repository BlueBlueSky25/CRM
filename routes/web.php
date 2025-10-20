<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CompanyChartController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;

// ==========================
// Public Routes (Login / Logout)
// ==========================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================
// CASCADE DROPDOWN ROUTES
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/get-regencies/{provinceId}', [UserController::class, 'getRegencies']);
    Route::get('/get-districts/{regencyId}', [UserController::class, 'getDistricts']);
    Route::get('/get-villages/{districtId}', [UserController::class, 'getVillages']);
});

// ==========================
// Protected Routes (Login + Permission)
// ==========================
Route::middleware(['auth', 'permission'])->group(function () {

    // ==========================
    // Dashboard
    // ==========================
    Route::get('/dashboard', [CompanyChartController::class, 'index'])->name('dashboard');

    // ==========================
    // Company Management
    // ==========================
    Route::get('/company', [CompanyController::class, 'index'])->name('company');
    Route::post('/company', [CompanyController::class, 'store'])->name('company.store');
    Route::put('/company/{id}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/company/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
    Route::get('/company/search', [CompanyController::class, 'search'])->name('company.search');

    
    // ==========================
    // ✅ Customer Management (FIXED)
    // ==========================
    // Halaman utama customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    
    // ✅ GET all customers (AJAX) - HARUS DI ATAS route {id}
    Route::get('/customers/list', [CustomerController::class, 'customers'])->name('customers.list');
    
    // Export & Import - HARUS DI ATAS route {id}
    Route::get('/customers/export/csv', [CustomerController::class, 'export'])->name('customers.export');
    Route::post('/customers/import', [CustomerController::class, 'import'])->name('customers.import');
    Route::post('/customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulkDelete');
    
    // Cascade Dropdown - HARUS DI ATAS route {id}
    Route::get('/customers/get-regencies/{provinceId}', [CustomerController::class, 'getRegencies']);
    Route::get('/customers/get-districts/{regencyId}', [CustomerController::class, 'getDistricts']);
    Route::get('/customers/get-villages/{districtId}', [CustomerController::class, 'getVillages']);
    
    // CRUD individual - HARUS PALING BAWAH
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    
    // ==========================
    // Calendar Page (React)
    // ==========================
    Route::get('/calendar', fn() => view('layout.react'))->name('calendar');

    // ==========================
    // Calendar API Routes
    // ==========================
    Route::prefix('api/calendar')->name('calendar.events.')->group(function () {
        Route::get('/events', [CalendarController::class, 'index'])->name('index');
        Route::post('/events', [CalendarController::class, 'store'])->name('store');
        Route::put('/events/{id}', [CalendarController::class, 'update'])->name('update');
        Route::delete('/events/{id}', [CalendarController::class, 'destroy'])->name('destroy');
    });

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
    Route::get('/pic', fn() => view('pages.pic'))->name('pic');
    Route::get('/salesvisit', fn() => view('pages.salesvisit'))->name('salesvisit');

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
    Route::get('/marketing/search', [SalesController::class, 'search'])->name('marketing.search');
    Route::post('/marketing/sales', [SalesController::class, 'store'])->name('marketing.sales.store');
    Route::put('/marketing/sales/{id}', [SalesController::class, 'update'])->name('marketing.sales.update');
    Route::delete('/marketing/sales/{id}', [SalesController::class, 'destroy'])->name('marketing.sales.destroy');

    // ==========================
    // BAR CHART ROUTES
    // ==========================
    Route::prefix('api/geographic/bar')->group(function () {
        Route::get('/distribution', [CompanyChartController::class, 'getGeoDistributionBar']);
        Route::get('/tier/{tier}', [CompanyChartController::class, 'getTierDetailBar']);
        Route::get('/export', [CompanyChartController::class, 'exportGeoDataBar']);
    });

    // ==========================
    // PIE CHART ROUTES
    // ==========================
    Route::prefix('api/geographic/pie')->group(function () {
        Route::get('/distribution', [CompanyChartController::class, 'getGeoDistributionPie']);
        Route::get('/tier/{tier}', [CompanyChartController::class, 'getTierDetailPie']);
        Route::get('/export', [CompanyChartController::class, 'exportGeoDataPie']);
    });
});