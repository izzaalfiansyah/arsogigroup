<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Authorization') !== '1234567890') {
            return response('Unauthorized', 400);
        }

        try {
            return $next($request)->header('Access-Control-Expose-Headers', '*');
        }
        catch (\Exception $e) {
            return response('Terjadi kesalahan', 400);
        }
    }
}
