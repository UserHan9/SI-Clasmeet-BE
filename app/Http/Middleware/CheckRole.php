<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecekan peran pengguna
        if ($request->user() && $request->user()->role !== 'admin') {
            // Jika peran bukan admin, Anda dapat memberikan respons atau mengarahkan pengguna
            return response('Unauthorized. Only admins are allowed.', 403);
        }

        return $next($request);
    }
}

