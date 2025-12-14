@extends('layouts.app')

@section('content')
<div class="bg-white shadow-xl rounded-xl p-6 max-w-3xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Detail Booking</h2>
        <a href="{{ route('admin.bookings.index') }}"
           class="bg-gray-200 px-4 py-2 rounded-lg">
            ← Kembali
        </a>
    </div>

    <div class="space-y-4 text-sm">
        <div>
            <strong>Kode Booking:</strong> {{ $booking->booking_code }}
        </div>

        <div>
            <strong>Nama Tamu:</strong> {{ $booking->user->name }}
        </div>

        <div>
            <strong>Kamar:</strong>
            {{ $booking->room->roomType->name }} —
            No {{ $booking->room->room_number }}
        </div>

        <div>
            <strong>Tanggal:</strong>
            {{ $booking->check_in->format('d M Y') }} -
            {{ $booking->check_out->format('d M Y') }}
        </div>

        <div>
            <strong>Total:</strong>
            Rp {{ number_format($booking->total_price,0,',','.') }}
        </div>

        <div>
            <strong>Status:</strong>
            <span class="px-3 py-1 rounded-full bg-gray-200 text-xs">
                {{ ucfirst($booking->status) }}
            </span>
        </div>

        @if($booking->special_notes)
        <div>
            <strong>Catatan:</strong>
            <p class="mt-1 text-gray-600">{{ $booking->special_notes }}</p>
        </div>
        @endif
    </div>

</div>
@endsection
