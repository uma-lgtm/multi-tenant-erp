<?php

use App\Http\Controllers\Central\SignupController;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return view('central.landing');
        })->name('landing');

        Route::get('/signup', [SignupController::class, 'showForm'])->name('signup');
        Route::post('/signup', [SignupController::class, 'register'])->name('signup.store');
    });
}
