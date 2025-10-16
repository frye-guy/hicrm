<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UpdateLastActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            // Minimal: mark activity. Replace with your UserSession logic later.
            Auth::user()->forceFill(['updated_at' => now()])->saveQuietly();
        }

        return $response;
    }
}