<?php

use App\Http\Controllers\Central\SignupController;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $index => $domain) {
    Route::domain($domain)->group(function () use ($index) {
        $suffix = $index === 0 ? '' : ".{$index}";

        Route::get('/', function () {
            return view('central.landing');
        })->name("landing{$suffix}");

        Route::get('/signup', [SignupController::class, 'showForm'])->name("signup{$suffix}");
        Route::post('/signup', [SignupController::class, 'register'])->name("signup.store{$suffix}");
    });
}
