<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderServiceController;
use App\Http\Controllers\OrdersController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});


Route::middleware('auth')->group(function () {
    // Auth
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Order Service

    Route::get('/dashboard/order-service', [OrderServiceController::class, 'create'])->name('dashboard.orderService');
    Route::post('/dashboard/order-service', [OrderServiceController::class, 'store'])->name('dashboard.orderService.store');
    Route::get('/dashboard/order-service/{order}', [OrderServiceController::class, 'show'])->name('dashboard.orderService.show');
    Route::post('/dashboard/order-service/validate-plate', [OrderServiceController::class, 'validatePlate'])->name('dashboard.orderService.validatePlate');
    Route::put('/dashboard/order-service/{order}', [OrderServiceController::class, 'update'])->name('dashboard.orderService.update');


    // Orders
    Route::get('/dashboard/orders', [OrdersController::class, 'index'])->name('dashboard.orders');
});
