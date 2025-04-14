<?php

use App\Http\Controllers\Admin\AttendanceRecapController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\ProfileController;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware(['is_employee'])->group(function () {
        Route::resource('/attendances', AttendanceController::class)->only(['index', 'store']);
        Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.checkin');
        Route::patch('/attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.checkout');
    });

    Route::middleware(['is_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('/employees', EmployeeController::class);
        Route::resource('/attendances', AttendanceRecapController::class)->except(['create', 'store']);
    });
});

Route::get('/employees/export', [EmployeeController::class, 'export'])->name('admin.employees.export');
Route::get('/attendances/export', [AttendanceRecapController::class, 'export'])->name('admin.attendances.export');

Route::resource('/attendances/permission', PermissionRequestController::class);
// TESTING
// Route::get('/test', function () {
//     return inertia('TestPage');
// })->name('test');
// Route::post('/test', function (Request $request) {
//     return dd($request->all(), $request->file('avatar'));
// })->name('test');
