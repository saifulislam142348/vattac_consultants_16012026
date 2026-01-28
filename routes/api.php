<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::post('/submissions', [\App\Http\Controllers\TaxSubmissionController::class, 'store']);

// OTP Routes
Route::post('/otp/send', [\App\Http\Controllers\OtpController::class, 'sendOtp']);
Route::post('/otp/verify', [\App\Http\Controllers\OtpController::class, 'verifyOtp']);

// System Config
Route::get('/system-config', [\App\Http\Controllers\SettingsController::class, 'publicConfig']);

// Public Content Route
Route::get('/content/{section}', [\App\Http\Controllers\SiteContentController::class, 'index']);

// Admin Routes (Authenticated)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Client Profile
    Route::get('/profile', [App\Http\Controllers\ClientProfileController::class, 'show']);
    Route::post('/profile', [App\Http\Controllers\ClientProfileController::class, 'update']);

    // Client Submissions
    Route::get('/submissions', [\App\Http\Controllers\TaxSubmissionController::class, 'index']);

    // Admin Submission Management
    Route::get('/admin/submissions', [\App\Http\Controllers\AdminController::class, 'index']);
    Route::get('/admin/reports', [\App\Http\Controllers\AdminController::class, 'reports']);
    Route::patch('/admin/submissions/{id}/status', [\App\Http\Controllers\AdminController::class, 'updateStatus']);
    Route::post('/admin/submissions/{id}/reply', [\App\Http\Controllers\AdminController::class, 'reply']);
    Route::patch('/admin/submissions/{id}/payment', [\App\Http\Controllers\AdminController::class, 'updatePayment']);
    Route::patch('/admin/submissions/{id}/return-details', [\App\Http\Controllers\AdminController::class, 'updateReturnDetails']);

    // Admin Content Management
    Route::post('/admin/content', [\App\Http\Controllers\SiteContentController::class, 'update']);

    // Admin Settings
    Route::get('/admin/settings', [\App\Http\Controllers\SettingsController::class, 'index']);
    Route::post('/admin/settings', [\App\Http\Controllers\SettingsController::class, 'update']);

    // Admin Documents & Missing Flags
    Route::get('/admin/documents', [\App\Http\Controllers\AdminController::class, 'documents']);
    Route::patch('/admin/submissions/{id}/missing-docs', [\App\Http\Controllers\AdminController::class, 'updateMissingDocs']);
});
