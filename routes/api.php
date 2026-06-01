<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\CampaignApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\StatsApiController;

/*
|--------------------------------------------------------------------------
| API Routes — AksiAlam v1
|--------------------------------------------------------------------------
|
| Semua route di file ini otomatis mendapat prefix /api.
| Versi API: v1 → prefix /api/v1/...
|
| Metode autentikasi yang didukung:
| 1. JWT (Bearer Token) — untuk operasi CRUD yang membutuhkan identitas user
| 2. API Key (Header X-API-KEY) — untuk endpoint publik/statistik
| 3. Basic Auth — login endpoint (email + password → JWT token)
|
*/

// =====================================================
// ROUTE v1: Dengan versioning
// =====================================================
Route::prefix('v1')->group(function () {

    // -------------------------------------------------
    // 1. AUTH: Basic Auth (login/register → dapat JWT token)
    // -------------------------------------------------
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    // -------------------------------------------------
    // 2. PUBLIC + API KEY: Statistik & Leaderboard
    //    Dilindungi oleh middleware api_key (header X-API-KEY)
    // -------------------------------------------------
    Route::middleware('api_key')->group(function () {
        Route::get('stats',       [StatsApiController::class, 'index']);
        Route::get('leaderboard', [StatsApiController::class, 'leaderboard']);
    });

    // -------------------------------------------------
    // 3. PUBLIC: Endpoint baca data tanpa autentikasi
    // -------------------------------------------------
    Route::get('reports',      [ReportApiController::class, 'index']);
    Route::get('reports/{id}', [ReportApiController::class, 'show']);
    Route::get('campaigns',      [CampaignApiController::class, 'index']);
    Route::get('campaigns/{id}', [CampaignApiController::class, 'show']);

    // -------------------------------------------------
    // 4. JWT PROTECTED: Operasi CRUD yang butuh login
    // -------------------------------------------------
    Route::middleware('auth:api')->group(function () {
        // Auth
        Route::post('logout',  [AuthController::class, 'logout']);

        // Reports CRUD (JWT required)
        Route::post('reports',        [ReportApiController::class, 'store']);
        Route::put('reports/{id}',    [ReportApiController::class, 'update']);
        Route::delete('reports/{id}', [ReportApiController::class, 'destroy']);

        // Campaigns CRUD (JWT required)
        Route::post('campaigns',            [CampaignApiController::class, 'store']);
        Route::put('campaigns/{id}',        [CampaignApiController::class, 'update']);
        Route::delete('campaigns/{id}',     [CampaignApiController::class, 'destroy']);
        Route::post('campaigns/{id}/join',  [CampaignApiController::class, 'join']);

        // User Profile & Data
        Route::get('user/profile',    [UserApiController::class, 'profile']);
        Route::get('user/reports',    [UserApiController::class, 'myReports']);
        Route::get('user/campaigns',  [UserApiController::class, 'myCampaigns']);
    });
});

// =====================================================
// BACKWARD COMPAT: Route lama tanpa prefix v1
// (agar tidak break existing client)
// =====================================================
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);
Route::get('reports',   [ReportApiController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::post('reports', [ReportApiController::class, 'store']);
});