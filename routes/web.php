<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\CampaignController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\OrganizerController;
use App\Http\Controllers\Web\AdminController;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Report;

// =====================================================
// 1. PUBLIC: Halaman Beranda / Landing Utama
// =====================================================
Route::get('/', [ReportController::class, 'index'])->name('home');

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
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard',          [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/lapor',              [ReportController::class, 'create'])->name('reports.create');
    Route::post('/lapor',             [ReportController::class, 'store'])->name('reports.store');
    Route::get('/campaign/{id}',      [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaign/{id}/join',[CampaignController::class, 'join'])->name('campaigns.join');
    
    // Reward Routes
    Route::get('/rewards',            [\App\Http\Controllers\Web\RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{id}/redeem',[\App\Http\Controllers\Web\RewardController::class, 'redeem'])->name('rewards.redeem');
});

// =====================================================
// 4. DASHBOARD ROLE: ORGANIZER (Komunitas Penggerak)
// =====================================================
Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard',           [OrganizerController::class, 'dashboard'])->name('dashboard');
    Route::post('/report/{id}/verify', [OrganizerController::class, 'verifyReport'])->name('reports.verify');
    Route::post('/campaign/create',    [OrganizerController::class, 'createCampaign'])->name('campaigns.create');
    Route::get('/campaign/{id}',       [CampaignController::class, 'show'])->name('campaigns.show');
});

// =====================================================
// 5. DASHBOARD ROLE: ADMIN (Super Admin)
// =====================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/report/{id}/status',    [AdminController::class, 'updateReportStatus'])->name('reports.status');
    Route::delete('/user/{id}',           [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/user/{id}/role',        [AdminController::class, 'changeUserRole'])->name('users.role');
});

// =====================================================
// 6. ROUTE UMUM: Shared Pages (authenticated)
// =====================================================
Route::middleware(['auth'])->group(function () {
    // Shared routes — bisa diakses oleh role apapun yang sudah login
    Route::get('/campaign/{id}',       [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaign/{id}/join', [CampaignController::class, 'join'])->name('campaigns.join');
    Route::get('/campaign/{id}/checkin', [CampaignController::class, 'checkin'])->name('campaigns.checkin');
    Route::get('/lapor',               [ReportController::class, 'create'])->name('reports.create');
    Route::post('/lapor',              [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{id}',        [ReportController::class, 'show'])->name('reports.show');
});