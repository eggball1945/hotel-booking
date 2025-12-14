<?php
// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'resepsionis'])) {
            return redirect()->route('tamu.dashboard')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}

// app/Http/Middleware/TamuMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TamuMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'tamu') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}

// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($role === 'admin' && $user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        if ($role === 'resepsionis' && !in_array($user->role, ['admin', 'resepsionis'])) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}