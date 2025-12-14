<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResepsionisController extends Controller
{
    /**
     * Dashboard Resepsionis
     */
    public function dashboard()
    {
        $today = Carbon::today();
        
        // Statistics
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'occupied_rooms' => Room::where('status', 'booked')->count(),
            'today_checkins' => Booking::whereDate('check_in', $today)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->count(),
            'today_checkouts' => Booking::whereDate('check_out', $today)
                ->whereIn('status', ['checked_in', 'checked_out'])
                ->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'current_guests' => Booking::where('status', 'checked_in')->count(),
        ];

        // Today's arrivals
        $todayArrivals = Booking::with(['room', 'user'])
            ->whereDate('check_in', $today)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->orderBy('check_in')
            ->limit(5)
            ->get();

        // Today's departures
        $todayDepartures = Booking::with(['room', 'user'])
            ->whereDate('check_out', $today)
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->orderBy('check_out')
            ->limit(5)
            ->get();

        // Current guests
        $currentGuests = Booking::with(['room', 'user'])
            ->where('status', 'checked_in')
            ->orderBy('check_in')
            ->limit(5)
            ->get();

        return view('resepsionis.dashboard', compact(
            'stats', 
            'todayArrivals', 
            'todayDepartures',
            'currentGuests'
        ));
    }

    /**
     * Semua Bookings
     */
    public function bookingsIndex(Request $request)
    {
        $query = Booking::with(['room', 'user', 'room.roomType']);

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('check_in', '<=', $date)
                  ->whereDate('check_out', '>=', $date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('room', function($q) use ($search) {
                      $q->where('room_number', 'LIKE', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('resepsionis.bookings.index', compact('bookings'));
    }

    /**
     * Booking Hari Ini
     */
    public function todayBookings()
    {
        $today = Carbon::today();
        
        $arrivals = Booking::with(['room', 'user'])
            ->whereDate('check_in', $today)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->orderBy('check_in')
            ->get();

        $departures = Booking::with(['room', 'user'])
            ->whereDate('check_out', $today)
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->orderBy('check_out')
            ->get();

        $current = Booking::with(['room', 'user'])
            ->where('status', 'checked_in')
            ->orderBy('check_in')
            ->get();

        return view('resepsionis.bookings.today', compact('arrivals', 'departures', 'current'));
    }

    /**
     * Detail Booking
     */
    public function bookingShow(Booking $booking)
    {
        $booking->load(['room', 'user', 'room.roomType', 'payments']);
        return view('resepsionis.bookings.show', compact('booking'));
    }

    /**
     * Check-in Guest
     */
    public function checkIn(Request $request, Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Booking harus dalam status confirmed untuk check-in.');
        }

        DB::beginTransaction();
        try {
            $booking->status = 'checked_in';
            $booking->actual_check_in = now();
            $booking->save();

            // Update room status
            $room = $booking->room;
            $room->status = 'booked';
            $room->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Check-in berhasil. Tamu sudah menginap.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal melakukan check-in: ' . $e->getMessage());
        }
    }

    /**
     * Check-out Guest
     */
    public function checkOut(Request $request, Booking $booking)
    {
        if ($booking->status !== 'checked_in') {
            return redirect()->back()
                ->with('error', 'Hanya tamu yang sedang menginap yang bisa check-out.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,debit_card,credit_card,transfer',
            'additional_charges' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Calculate final amount
            $additionalCharges = $request->additional_charges ?? 0;
            $totalAmount = $booking->total_price + $additionalCharges;

            // Record payment
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'paid',
                'notes' => $request->notes,
                'paid_at' => now(),
            ]);

            // Update booking
            $booking->status = 'checked_out';
            $booking->actual_check_out = now();
            $booking->additional_charges = $additionalCharges;
            $booking->total_paid = $totalAmount;
            $booking->save();

            // Update room status
            $room = $booking->room;
            $room->status = 'available';
            $room->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Check-out berhasil. Total pembayaran: Rp ' . number_format($totalAmount, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal melakukan check-out: ' . $e->getMessage());
        }
    }

    /**
     * Update Booking Status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->status = $request->status;
        $booking->save();

        if ($request->status == 'cancelled') {
            // Free up the room
            $room = $booking->room;
            $room->status = 'available';
            $room->save();
        }

        return redirect()->back()
            ->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Record Payment
     */
    public function recordPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,debit_card,credit_card,transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'paid',
            'notes' => $request->notes,
            'paid_at' => now(),
        ]);

        // Update booking total paid
        $booking->total_paid = ($booking->total_paid ?? 0) + $request->amount;
        
        // Jika sudah lunas, update status
        if ($booking->total_paid >= $booking->total_price) {
            $booking->payment_status = 'paid';
        }
        
        $booking->save();

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    /**
     * Daftar Kamar
     */
    public function roomsIndex()
    {
        $rooms = Room::with('roomType')
            ->orderBy('room_number')
            ->get();

        $roomTypes = \App\Models\RoomType::all();

        return view('resepsionis.rooms.index', compact('rooms', 'roomTypes'));
    }

    /**
     * Detail Kamar
     */
    public function roomShow(Room $room)
    {
        $room->load('roomType');
        
        // Get current booking if any
        $currentBooking = Booking::where('room_id', $room->id)
            ->where('status', 'checked_in')
            ->first();

        // Get room history
        $roomHistory = Booking::with('user')
            ->where('room_id', $room->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('resepsionis.rooms.show', compact('room', 'currentBooking', 'roomHistory'));
    }

    /**
     * Daftar Tamu
     */
    public function guestsIndex()
    {
        $guests = Booking::with(['user', 'room'])
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->orderBy('check_in', 'desc')
            ->paginate(20);

        return view('resepsionis.guests.index', compact('guests'));
    }

    /**
     * Tamu yang sedang check-in
     */
    public function checkInGuests()
    {
        $guests = Booking::with(['user', 'room'])
            ->where('status', 'checked_in')
            ->orderBy('check_in')
            ->get();

        return view('resepsionis.guests.checkin', compact('guests'));
    }

    /**
     * Tamu saat ini
     */
    public function currentGuests()
    {
        $guests = Booking::with(['user', 'room.roomType'])
            ->where('status', 'checked_in')
            ->orderBy('check_in')
            ->get();

        return view('resepsionis.guests.current', compact('guests'));
    }

    /**
     * Quick Check-in
     */
    public function quickCheckIn(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|exists:bookings,booking_code',
        ]);

        $booking = Booking::where('booking_code', $request->booking_code)->first();

        if ($booking->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Booking harus dalam status confirmed.'
            ]);
        }

        DB::beginTransaction();
        try {
            $booking->status = 'checked_in';
            $booking->actual_check_in = now();
            $booking->save();

            $room = $booking->room;
            $room->status = 'booked';
            $room->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil untuk ' . $booking->user->name,
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal check-in: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Quick Check-out
     */
    public function quickCheckOut(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|exists:bookings,booking_code',
            'payment_method' => 'required|in:cash,debit_card,credit_card,transfer',
        ]);

        $booking = Booking::where('booking_code', $request->booking_code)->first();

        if ($booking->status !== 'checked_in') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya tamu yang sedang menginap yang bisa check-out.'
            ]);
        }

        DB::beginTransaction();
        try {
            // Record payment
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'payment_method' => $request->payment_method,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Update booking
            $booking->status = 'checked_out';
            $booking->actual_check_out = now();
            $booking->total_paid = $booking->total_price;
            $booking->payment_status = 'paid';
            $booking->save();

            // Update room
            $room = $booking->room;
            $room->status = 'available';
            $room->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil. Total: Rp ' . number_format($booking->total_price, 0, ',', '.'),
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal check-out: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Laporan Harian
     */
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $checkIns = Booking::with(['user', 'room'])
            ->whereDate('check_in', $selectedDate)
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->get();

        $checkOuts = Booking::with(['user', 'room'])
            ->whereDate('check_out', $selectedDate)
            ->whereIn('status', ['checked_out'])
            ->get();

        $payments = Payment::whereDate('paid_at', $selectedDate)
            ->with('booking.user')
            ->get();

        $totalRevenue = $payments->sum('amount');

        return view('resepsionis.reports.daily', compact(
            'selectedDate', 
            'checkIns', 
            'checkOuts', 
            'payments', 
            'totalRevenue'
        ));
    }

    /**
     * Laporan Occupancy
     */
    public function occupancyReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));

        $rooms = Room::with(['bookings' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('check_in', [$startDate, $endDate])
                  ->orWhereBetween('check_out', [$startDate, $endDate]);
        }])->get();

        // Calculate occupancy rate
        $totalRooms = Room::count();
        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $totalRoomNights = $totalRooms * $totalDays;

        $bookedRoomNights = 0;
        foreach ($rooms as $room) {
            foreach ($room->bookings as $booking) {
                $checkIn = Carbon::parse(max($booking->check_in, $startDate));
                $checkOut = Carbon::parse(min($booking->check_out, $endDate));
                $bookedRoomNights += $checkIn->diffInDays($checkOut);
            }
        }

        $occupancyRate = $totalRoomNights > 0 ? ($bookedRoomNights / $totalRoomNights) * 100 : 0;

        return view('resepsionis.reports.occupancy', compact(
            'rooms',
            'startDate',
            'endDate',
            'occupancyRate',
            'totalRooms',
            'bookedRoomNights',
            'totalRoomNights'
        ));
    }
}