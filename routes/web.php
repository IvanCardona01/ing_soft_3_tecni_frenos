<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.page-login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('dashboard');
})->name('login.submit');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard/order-service', [DashboardController::class, 'orderService'])->name('dashboard.orderService');

Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
