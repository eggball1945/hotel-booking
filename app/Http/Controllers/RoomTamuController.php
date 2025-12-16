<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoomTamuController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->paginate(9);

        return view('tamu.rooms.index', compact('rooms'));
    }

    // Show room detail differently depending on user role
    public function show(Room $room)
    {
        $room->load('roomType');

        $user = Auth::user();

        // If user is admin -> show admin room view
        if ($user && $user->role === 'admin') {
            return view('admin.rooms.show', compact('room'));
        }

        // If user is resepsionis -> show resepsionis room view with extra context
        if ($user && $user->role === 'resepsionis') {
            $currentBooking = Booking::with(['user'])
                ->where('room_id', $room->id)
                ->where('status', 'checked_in')
                ->first();

            $roomHistory = Booking::with('user')
                ->where('room_id', $room->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('resepsionis.rooms.show', compact('room', 'currentBooking', 'roomHistory'));
        }

        // If user is tamu -> show tamu room detail (with Pesan button)
        if ($user && $user->role === 'tamu') {
            return view('tamu.rooms.show', compact('room'));
        }

        // Guest or other roles -> fall back to public show if exists, otherwise tamu view
        if (view()->exists('public.rooms.show')) {
            return view('public.rooms.show', compact('room'));
        }

        return view('tamu.rooms.show', compact('room'));
    }
}
