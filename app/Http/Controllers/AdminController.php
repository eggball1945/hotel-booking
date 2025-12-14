<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /* ======================
       DASHBOARD
    ====================== */
    public function dashboard()
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'resepsionis'])) {
            abort(403, 'Unauthorized');
        }

        if ($user->role === 'admin') {
            return view('admin.dashboard', [
                'users'    => User::count(),
                'rooms'    => Room::count(),
                'bookings' => Booking::count(),
                'user'     => $user
            ]);
        }

        return view('admin.dashboard', [
            'bookings' => Booking::count(),
            'user'     => $user
        ]);
    }

    /* ======================
       USERS (RESOURCE FIX)
    ====================== */

    // 🔥 INI YANG HILANG SEBELUMNYA
    public function index()
    {
        return $this->usersIndex();
    }

    public function create()
    {
        return $this->usersCreate();
    }

    public function store(Request $request)
    {
        return $this->usersStore($request);
    }

    public function edit(User $user)
    {
        $this->authorizeRole('admin');
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeRole('admin');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,resepsionis,tamu',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        return $this->usersDestroy($user);
    }

    /* ======================
       USERS (OLD METHODS)
    ====================== */

    public function usersIndex()
    {
        $this->authorizeRole('admin');
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        $this->authorizeRole('admin');
        return view('admin.users.create');
    }

    public function usersStore(Request $request)
    {
        $this->authorizeRole('admin');

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:admin,resepsionis,tamu'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success','User berhasil ditambahkan');
    }

    public function usersDestroy(User $user)
    {
        $this->authorizeRole('admin');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /* ======================
       BOOKINGS
    ====================== */

    public function bookingsIndex()
    {
        $this->authorizeRole(['admin','resepsionis']);

        $bookings = Booking::with(['user','room'])
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /* ======================
       HELPER
    ====================== */
    private function authorizeRole($roles)
    {
        $roles = (array) $roles;

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized');
        }
    }
}