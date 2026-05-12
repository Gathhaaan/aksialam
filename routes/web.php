<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk melihat halaman utama eksplorasi AksiAlam
Route::get('/', [ReportController::class, 'index']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/lapor', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/lapor', [ReportController::class, 'store'])->name('reports.store');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);