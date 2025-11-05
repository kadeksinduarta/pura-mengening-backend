<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::get('/dashboard/booking', [BookingController::class, 'index'])->name('dashboard.booking');
// });
