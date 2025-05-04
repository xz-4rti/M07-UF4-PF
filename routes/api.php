<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    // All authenticated users can view products
    Route::get('/products', [ProductController::class, 'index']);
    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::post('/products',         [ProductController::class, 'store']);
        Route::put('/products/{id}',     [ProductController::class, 'update']);
        Route::delete('/products/{id}',  [ProductController::class, 'destroy']);
    });
});
