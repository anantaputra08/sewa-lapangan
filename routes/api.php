<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rute-rute ini dimuat oleh RouteServiceProvider dan secara otomatis
| diberi prefix '/api'.
|
*/

// Route publik untuk login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route yang dilindungi oleh Sanctum
Route::middleware(['auth:sanctum'])->group(function () {
    // Route untuk mendapatkan data user (seperti yang Anda punya)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route untuk logout
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/bookings/check-availability', [BookingController::class, 'checkAvailability']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings', [BookingController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'show']); 
    Route::post('/profile', [ProfileController::class, 'update']);
});

// Route untuk mendapatkan daftar lapangan
    Route::get('/lapangans/available', [BookingController::class, 'getAvailableLapangans']);
