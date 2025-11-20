<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StudentAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Student Auth
Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [StudentAuthController::class, 'register']);
Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [StudentAuthController::class, 'login']);
Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');

// Student Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AppointmentController::class, 'index'])->name('student.dashboard');
    Route::get('/appointment/create', [AppointmentController::class, 'create'])->name('appointment.create');
    Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware(\App\Http\Middleware\AdminAuthMiddleware::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::put('/appointment/{appointment}', [AdminController::class, 'update'])->name('admin.update');
    });
});
