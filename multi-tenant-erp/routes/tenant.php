<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\ProductController;

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
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
    });
});
