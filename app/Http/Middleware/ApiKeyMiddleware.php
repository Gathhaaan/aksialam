<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware API Key Authentication.
 * Memeriksa header X-API-KEY pada setiap request API publik.
 * Key disimpan di file .env dengan variabel API_KEY.
 * 
 * Digunakan untuk endpoint publik yang membutuhkan identifikasi client
 * namun tidak memerlukan autentikasi user (JWT).
 */
class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $validKey = config('app.api_key');

        if (!$apiKey || $apiKey !== $validKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key tidak valid atau tidak ditemukan. Sertakan header X-API-KEY.',
            ], 401);
        }

        return $next($request);
    }
}
