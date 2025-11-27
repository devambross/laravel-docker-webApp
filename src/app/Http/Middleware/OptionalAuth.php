<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptionalAuth
{
    /**
     * Handle an incoming request.
     * Permite acceso sin autenticación a rutas API
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
