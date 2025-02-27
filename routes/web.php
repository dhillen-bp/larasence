<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Index');
    })->name('dashboard');

    Route::resource('/attendances', AttendanceController::class)->only(['index', 'store']);
    Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.checkin');
    Route::patch('/attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.checkout');

    Route::get('/test', function () {
        return Inertia::render('Test');
    })->name('test');
});
