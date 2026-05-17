<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\CampaignController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\OrganizerController;
use App\Http\Controllers\Web\AdminController;

// =====================================================
// 1. GUEST: Halaman Landing Utama (Selalu tampil)
// =====================================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// =====================================================
// 2. AUTH: Proses Masuk & Daftar Akun (hanya tamu)
// =====================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =====================================================
// 3. DASHBOARD ROLE: USER (Relawan Biasa)
// =====================================================
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard',          [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/lapor',              [ReportController::class, 'create'])->name('reports.create');
    Route::post('/lapor',             [ReportController::class, 'store'])->name('reports.store');
    Route::get('/campaign/{id}',      [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaign/{id}/join',[CampaignController::class, 'join'])->name('campaigns.join');
});

// =====================================================
// 4. DASHBOARD ROLE: ORGANIZER (Komunitas Penggerak)
// =====================================================
Route::middleware(['auth'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard',           [OrganizerController::class, 'dashboard'])->name('dashboard');
    Route::post('/report/{id}/verify', [OrganizerController::class, 'verifyReport'])->name('reports.verify');
    Route::post('/campaign/create',    [OrganizerController::class, 'createCampaign'])->name('campaigns.create');
    Route::get('/campaign/{id}',       [CampaignController::class, 'show'])->name('campaigns.show');
});

// =====================================================
// 5. DASHBOARD ROLE: ADMIN (Super Admin)
// =====================================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/report/{id}/status',    [AdminController::class, 'updateReportStatus'])->name('reports.status');
    Route::delete('/user/{id}',           [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/user/{id}/role',        [AdminController::class, 'changeUserRole'])->name('users.role');
});

// =====================================================
// 6. ROUTE UMUM (beranda lama & campaign publik)
// =====================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/beranda',             [ReportController::class, 'index'])->name('home');
    Route::get('/campaign/{id}',       [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaign/{id}/join', [CampaignController::class, 'join'])->name('campaigns.join');
    Route::get('/lapor',               [ReportController::class, 'create'])->name('reports.create');
    Route::post('/lapor',              [ReportController::class, 'store'])->name('reports.store');
});