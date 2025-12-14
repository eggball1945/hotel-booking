<?php

namespace App\Http\Controllers\Tamu;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // List kamar tersedia
    public function index()
    {
        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->orderBy('room_number')
            ->paginate(9);

        return view('tamu.bookings.index', compact('rooms'));
    }

    // Form booking
    public function create($room_id)
    {
        $room = Room::with('roomType')
            ->where('id', $room_id)
            ->where('status', 'available')
            ->firstOrFail();

        return view('tamu.bookings.create', compact('room'));
    }

    // Simpan booking
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'special_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $room = Room::with('roomType')->findOrFail($request->room_id);

            if ($room->status !== 'available') {
                return back()->with('error', 'Kamar tidak tersedia')->withInput();
            }

            $checkIn  = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();

            // Cek bentrok
            $exists = Booking::where('room_id', $room->id)
                ->whereIn('status', ['pending','confirmed','checked_in'])
                ->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
                })->exists();

            if ($exists) {
                return back()->with('error', 'Kamar sudah dibooking')->withInput();
            }

            $totalNights = $checkOut->diffInDays($checkIn);
            $totalPrice  = $totalNights * ($room->roomType->price ?? 0);

            $booking = Booking::create([
                'booking_code' => 'BK-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_nights' => $totalNights,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'special_notes' => $request->special_notes,
            ]);

            $room->update(['status' => 'booked']);

            DB::commit();

            return redirect()
                ->route('tamu.bookings.history')
                ->with('success', 'Booking berhasil! Kode: '.$booking->booking_code);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // Riwayat booking
    public function history()
    {
        $bookings = Booking::with(['room.roomType'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('tamu.bookings.history', compact('bookings'));
    }

    // Detail booking
    public function show($id)
    {
        $booking = Booking::with(['room.roomType'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('tamu.bookings.show', compact('booking'));
    }

    // Batalkan booking
    public function cancel($id)
    {
        DB::transaction(function () use ($id) {
            $booking = Booking::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->firstOrFail();

            $booking->update(['status' => 'cancelled']);
            $booking->room->update(['status' => 'available']);
        });

        return back()->with('success', 'Booking dibatalkan');
    }

    // Upload bukti pembayaran
    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        if ($booking->payment_proof) {
            Storage::delete('public/payments/'.$booking->payment_proof);
        }

        $fileName = time().'_'.$request->file('payment_proof')->getClientOriginalName();
        $request->file('payment_proof')->storeAs('public/payments', $fileName);

        $booking->update(['payment_proof' => $fileName]);

        return back()->with('success', 'Bukti pembayaran diupload');
    }
}
