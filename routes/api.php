<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportApiController;

// Rute Publik (Tidak butuh token)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('reports', [ReportApiController::class, 'index']); // Publik bisa lihat data laporan

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('reports', [ReportApiController::class, 'store']);
});