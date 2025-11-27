<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('appointment.create');
});

// Student Routes
Route::get('/appointment/create', [AppointmentController::class, 'create'])->name('appointment.create');
Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');

// Admin Routes
Route::prefix('sitc-admin-area')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('sitc-admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('sitc-admin.authenticate');
    Route::post('/logout', [AdminController::class, 'logout'])->name('sitc-admin.logout');

    Route::middleware(\App\Http\Middleware\AdminAuthMiddleware::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('sitc-admin.dashboard');
        Route::put('/appointment/{appointment}', [AdminController::class, 'update'])->name('sitc-admin.update');
        Route::delete('/appointment/{appointment}', [AdminController::class, 'destroy'])->name('sitc-admin.delete');
    });
});
