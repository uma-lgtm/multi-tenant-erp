<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\DomainSettingsController;

Route::middleware([
    'web',
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    require __DIR__ . '/tenant-auth.php';

    Route::middleware('auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('dashboard');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('invoices', InvoiceController::class)->only([
            'index', 'create', 'store', 'show',
        ]);

        Route::resource('products', ProductController::class)->only([
            'index', 'create', 'store', 'show',
        ]);

        Route::get('/settings/domain', [DomainSettingsController::class, 'index'])->name('domain.settings');
        Route::post('/settings/domain', [DomainSettingsController::class, 'store'])->name('domain.store');
        Route::post('/settings/domain/{domain}/verify', [DomainSettingsController::class, 'verify'])->name('domain.verify');
        Route::delete('/settings/domain/{domain}', [DomainSettingsController::class, 'destroy'])->name('domain.destroy');
    });
});
