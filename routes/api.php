<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/products', [ProductController::class, 'index']);
    
//     // Protected routes for admin
//     Route::middleware('admin')->group(function () {
//         Route::post('/products', [ProductController::class, 'store']);
//         Route::put('/products/{id}', [ProductController::class, 'update']);
//         Route::delete('/products/{id}', [ProductController::class, 'destroy']);
//     });

//     Route::post('/logout', [AuthController::class, 'logout']);
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $req) => $req->user());
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store'])->middleware('admin');
    Route::put('/products/{id}', [ProductController::class, 'update'])->middleware('admin');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('admin');
    Route::post('/logout', [AuthController::class, 'logout']);
});

