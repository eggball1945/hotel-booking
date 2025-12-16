@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Booking {{ $booking->booking_code }}</h1>

<div class="bg-white p-4 rounded shadow">
    <p><strong>Guest:</strong> {{ $booking->user->name }}</p>
    <p><strong>Room:</strong> {{ $booking->room->room_number }} ({{ optional($booking->room->roomType)->name ?? '-' }})</p>
    <p><strong>Check In:</strong> {{ $booking->check_in }}</p>
    <p><strong>Check Out:</strong> {{ $booking->check_out }}</p>
    <p><strong>Total:</strong> {{ $booking->total_price }}</p>
    <p><strong>Status:</strong> {{ $booking->status }}</p>

    @if(auth()->user()->role === 'tamu' && $booking->status === 'pending')
        <p class="mt-3">Silakan lakukan pembayaran ke rekening XXX dan unggah bukti.</p>
        <!-- implement upload form for bukti pembayaran jika perlu -->
    @endif

    @if(auth()->user()->role !== 'tamu')
        <a href="{{ route('bookings.edit', $booking) }}" class="mt-3 inline-block bg-blue-500 text-white px-3 py-2 rounded">Manage Booking</a>
    @endif
</div>
@endsection
