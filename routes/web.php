<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//    ->middleware(['auth' , 'verified'])

require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';
