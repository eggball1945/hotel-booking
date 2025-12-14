<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * List booking
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'room.roomType'])
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Detail booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.roomType']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Form edit booking
     */
    public function edit(Booking $booking)
    {
        $booking->load(['room.roomType']);

        $statuses = [
            'pending'     => 'Pending',
            'confirmed'   => 'Confirmed',
            'checked_in'  => 'Checked In',
            'checked_out' => 'Checked Out',
            'cancelled'   => 'Cancelled',
        ];

        return view('admin.bookings.edit', compact('booking', 'statuses'));
    }

    /**
     * Update booking
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'special_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $booking) {

            $booking->update([
                'status' => $request->status,
                'special_notes' => $request->special_notes,
            ]);

            if ($booking->room) {
                if (in_array($request->status, ['cancelled', 'checked_out'])) {
                    $booking->room->update(['status' => 'available']);
                } else {
                    $booking->room->update(['status' => 'booked']);
                }
            }
        });

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking berhasil diperbarui');
    }

    /**
     * Hapus booking
     */
    public function destroy(Booking $booking)
    {
        DB::transaction(function () use ($booking) {

            if ($booking->room) {
                $booking->room->update(['status' => 'available']);
            }

            $booking->delete();
        });

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus');
    }
}
