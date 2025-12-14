<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TamuMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'tamu') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses.');
        }
        return $next($request);
    }
}
