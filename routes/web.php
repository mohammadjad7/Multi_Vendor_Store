<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


    Route::get('/admin', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

    
//    ->middleware(['auth' , 'verified'])

require __DIR__.'/auth.php';
