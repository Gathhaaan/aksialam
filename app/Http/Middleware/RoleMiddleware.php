<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Penggunaan di route: middleware('role:admin') atau middleware('role:admin,organizer')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Satu atau lebih role yang diizinkan
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, $roles)) {
            // Redirect ke dashboard yang sesuai berdasarkan role user
            if ($user) {
                return match ($user->role) {
                    'admin'     => redirect()->route('admin.dashboard'),
                    'organizer' => redirect()->route('organizer.dashboard'),
                    default     => redirect()->route('home'),
                };
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
