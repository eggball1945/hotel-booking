<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomTamuController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->paginate(9);

        return view('tamu.rooms.index', compact('rooms'));
    }

    // ❌ JANGAN ARAHKAN KE bookings.index
    public function show(Room $room)
    {
        return redirect()->route('tamu.bookings.create', $room->id);
    }
}
