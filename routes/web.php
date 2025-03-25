<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.page-login');
})->name('login');


Route::post('/login', function () {

})->name('login.submit');

