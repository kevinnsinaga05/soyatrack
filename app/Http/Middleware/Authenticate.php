<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Jika belum login, redirect ke login page
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Jika sudah login, lanjutkan request
        return $next($request);
    }
}
