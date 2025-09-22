<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PpicMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'Staff PPIC') {
            return $next($request);
        }
        return redirect()->route('ppic.login.index')->with('error', 'Akses ditolak! Silahkan Coba Lagi.');
    }
}
