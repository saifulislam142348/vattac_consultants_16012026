<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin Routes
    Route::get('/admin/submissions', [\App\Http\Controllers\AdminController::class, 'index']);
    Route::patch('/admin/submissions/{id}/status', [\App\Http\Controllers\AdminController::class, 'updateStatus']);
});

Route::post('/submissions', [\App\Http\Controllers\TaxSubmissionController::class, 'store']);
