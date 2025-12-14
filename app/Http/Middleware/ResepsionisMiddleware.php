<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResepsionisMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (!in_array(Auth::user()->role, ['admin', 'resepsionis'])) {
            return redirect()->route('dashboard.redirect');
        }

        return $next($request);
    }
}