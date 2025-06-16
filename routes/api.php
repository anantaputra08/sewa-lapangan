<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;
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

Route::middleware(['auth:sanctum'])->prefix('admin')->name('admin.api.')->group(function () {
    // Rute untuk User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}', [AdminUserController::class, 'update'])->name('users.update'); // Menggunakan POST untuk update dengan file
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::get('/bookings/creation-data', [AdminBookingController::class, 'getCreationData']);
    Route::post('/bookings', [AdminBookingController::class, 'store']);
    Route::post('/bookings/update/{booking}', [AdminBookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy']);

    Route::get('bookings/available-sessions', [AdminBookingController::class, 'getAvailableSessions']);
});