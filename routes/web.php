<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
});

// Route::middleware(['auth'])->group(function () {
Route::resource('/attendances', AttendanceController::class);
// });
