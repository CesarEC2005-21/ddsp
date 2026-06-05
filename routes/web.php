<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LaboratoryController;
// use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\RepresentativeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZonaController;
use App\Http\Controllers\Admin\UnidadMedidaController;
use App\Http\Controllers\Admin\SecurityController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/nosotros', [LandingController::class, 'nosotros'])->name('nosotros');
Route::get('/ejecutivos', [LandingController::class, 'about'])->name('about');
Route::get('/productos', [LandingController::class, 'products'])->name('products');
Route::get('/producto/{product}', [LandingController::class, 'productDetail'])->name('product.detail');
Route::get('/contacto', [LandingController::class, 'contact'])->name('contact');
Route::post('/contacto', [LandingController::class, 'processContact'])->name('contact.post');
Route::get('/api/search-products', [LandingController::class, 'searchProducts'])->name('api.products.search');
Route::get('/noticias', [LandingController::class, 'noticias'])->name('noticias');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/login/verify', [LoginController::class, 'showTwoFactorForm'])->name('2fa.index');
Route::post('/login/verify', [LoginController::class, 'verifyTwoFactor'])->name('2fa.verify');
Route::post('/login/resend', [LoginController::class, 'resendTwoFactor'])->name('2fa.resend');
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
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
        Route::get('products/{product}/history', [ProductController::class, 'priceHistory'])->name('products.history');
        Route::delete('products/delete-by-lab/{laboratory}', [ProductController::class, 'deleteByLab'])->name('products.deleteByLab');
        Route::resource('products', ProductController::class);
        Route::patch('laboratories/{laboratory}/toggle-top', [\App\Http\Controllers\Admin\LaboratoryController::class, 'toggleTop'])->name('laboratories.toggle-top');
        Route::resource('laboratories', LaboratoryController::class);
        Route::resource('zonas', ZonaController::class);
        Route::resource('unidad-medidas', UnidadMedidaController::class);
        // Route::resource('pharmacies', PharmacyController::class);
        Route::resource('representatives', RepresentativeController::class);
        Route::resource('noticias', \App\Http\Controllers\Admin\NoticiaController::class);
        
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users/{user}/block-history', [UserController::class, 'blockHistory'])->name('users.block-history');
        Route::resource('users', UserController::class);
        
        // Banners
        Route::get('banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
        Route::patch('banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
        
        // Reportes, Backups y Configuración
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/customers', [\App\Http\Controllers\Admin\ReportController::class, 'customers'])->name('reports.customers');
        Route::get('reports/quotations', [\App\Http\Controllers\Admin\ReportController::class, 'quotations'])->name('reports.quotations');
        Route::get('reports/products', [\App\Http\Controllers\Admin\ReportController::class, 'products'])->name('reports.products');
        Route::get('backups', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
        Route::post('backups/generate', [\App\Http\Controllers\Admin\BackupController::class, 'generate'])->name('backups.generate');
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');
        
        // Seguridad
        Route::get('security/audit', [SecurityController::class, 'audit'])->name('security.audit');
        Route::get('security/access', [SecurityController::class, 'access'])->name('security.access');
        
        // Quotations (Pedidos)
        Route::get('quotations', [\App\Http\Controllers\Admin\QuotationController::class, 'index'])->name('quotations.index');
        Route::get('quotations/{quotation}', [\App\Http\Controllers\Admin\QuotationController::class, 'show'])->name('quotations.show');
        Route::get('quotations/{quotation}/pdf', [\App\Http\Controllers\Admin\QuotationController::class, 'exportPdf'])->name('quotations.pdf');
        Route::get('quotations/{quotation}/excel', [\App\Http\Controllers\Admin\QuotationController::class, 'exportExcel'])->name('quotations.excel');
        Route::patch('quotations/{quotation}/status', [\App\Http\Controllers\Admin\QuotationController::class, 'updateStatus'])->name('quotations.status');
    });
});
