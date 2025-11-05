<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;

// 🔹 Endpoint untuk form booking dari React
Route::post('/booking', [BookingController::class, 'store']);

// 🔹 Endpoint untuk mengambil data blog
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{id}', [BlogController::class, 'show']);

// 🔒 Endpoint yang dilindungi (hanya untuk admin login)
Route::middleware('auth:sanctum')->group(function () {
    // 🧾 Manajemen Booking (CRUD)
    Route::get('dashboard/booking', [BookingController::class, 'index']);
    Route::post('dashboard/booking', [BookingController::class, 'store']);
    Route::get('dashboard/booking/{id}', [BookingController::class, 'show']);
    Route::put('dashboard/booking/{id}', [BookingController::class, 'update']);
    Route::delete('dashboard/booking/{id}', [BookingController::class, 'destroy']);

    // 📰 Manajemen Blog (CRUD)
    Route::get('dashboard/blog', [BlogController::class, 'index']);
    Route::get('dashboard/blog/{id}', [BlogController::class, 'show']);
    Route::post('dashboard/blog', [BlogController::class, 'store']);
    Route::put('dashboard/blog/{id}', [BlogController::class, 'update']);
    Route::delete('dashboard/blog/{id}', [BlogController::class, 'destroy']);
});

// 👨‍💼 Admin Authentication
Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::middleware('auth:sanctum')->get('/admin/me', function (Request $request) {
    return response()->json($request->user());
});
