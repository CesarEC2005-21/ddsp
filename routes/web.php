<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LaboratoryController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\RepresentativeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZonaController;
use App\Http\Controllers\Admin\UnidadMedidaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/nosotros', [LandingController::class, 'about'])->name('about');
Route::get('/productos', [LandingController::class, 'products'])->name('products');
Route::get('/producto/{product}', [LandingController::class, 'productDetail'])->name('product.detail');
Route::get('/contacto', [LandingController::class, 'contact'])->name('contact');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Cart & Quotation Routes
Route::get('/carrito', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

Route::post('/quotation/store', [\App\Http\Controllers\QuotationController::class, 'store'])->name('quotation.store');
Route::get('/quotation/success', [\App\Http\Controllers\QuotationController::class, 'success'])->name('quotation.success');

// Admin Routes (Intranet)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Protegido por rol (ejemplo: admin y supervisor pueden ver/crear productos)
    Route::middleware(['role:ing_sistemas,admin,supervisor'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::patch('laboratories/{laboratory}/toggle-top', [\App\Http\Controllers\Admin\LaboratoryController::class, 'toggleTop'])->name('laboratories.toggle-top');
        Route::resource('laboratories', LaboratoryController::class);
        Route::resource('zonas', ZonaController::class);
        Route::resource('unidad-medidas', UnidadMedidaController::class);
        Route::resource('pharmacies', PharmacyController::class);
        Route::resource('representatives', RepresentativeController::class);
        Route::resource('users', UserController::class);
        
        // Reportes, Backups y Configuración
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('backups', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
        Route::post('backups/generate', [\App\Http\Controllers\Admin\BackupController::class, 'generate'])->name('backups.generate');
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    });
});
