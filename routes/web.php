<?php

use Illuminate\Support\Facades\Route;

Route::view('/login', 'login')->name('login');
Route::view('/products', 'products')->middleware('auth');
