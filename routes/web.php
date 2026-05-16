<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\CampaignController; // Namespace sudah diarahkan ke folder Web

// 1. GUEST: Halaman Landing Utama (Sebelum Login)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// 2. AUTH: Proses Masuk & Daftar Akun
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. MIDDLWARE AUTH: Fitur Aplikasi Dalam (Wajib Login)
Route::middleware(['auth'])->group(function () {
    // Beranda Dashboard Internal
    Route::get('/beranda', [ReportController::class, 'index'])->name('home');
    
    // Alur Pengaduan & Upload Foto
    Route::get('/lapor', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/lapor', [ReportController::class, 'store'])->name('reports.store');

    // Alur Detail Aksi Swadaya & Pendaftaran Relawan
    Route::get('/campaign/{id}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaign/{id}/join', [CampaignController::class, 'join'])->name('campaigns.join');
});