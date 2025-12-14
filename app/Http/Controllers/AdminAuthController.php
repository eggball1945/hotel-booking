<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN ADMIN / RESEPSIONIS
    |--------------------------------------------------------------------------
    */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // CEK USER + PASSWORD (PLAIN)
        if (!$user || $user->password !== $request->password) {
            return back()->with('error', 'Email atau password salah');
        }

        // CEK ROLE
        if (!in_array($user->role, ['admin', 'resepsionis'])) {
            return back()->with('error', 'Anda tidak memiliki akses admin');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER ADMIN / RESEPSIONIS
    |--------------------------------------------------------------------------
    */
    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:admin,resepsionis',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // simpan plaintext
            'role'     => $request->role,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin / Resepsionis berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
