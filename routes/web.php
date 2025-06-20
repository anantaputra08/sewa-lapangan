<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionHourController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //
    Route::resource('categories', CategoryController::class);
    Route::resource('lapangans', LapanganController::class);
    Route::resource('session-hours', SessionHourController::class);
    Route::resource('days', DayController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
