@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">New Booking</h1>

<form action="{{ route('bookings.store') }}" method="POST">
    @csrf
    <div class="mb-2">
        <label>Room</label>
        <select name="room_id" class="w-full p-2">
            @foreach($rooms as $r)
                <option value="{{ $r->id }}" {{ request('room_id') == $r->id ? 'selected':'' }}>
                    {{ $r->room_number }} - {{ optional($r->roomType)->name ?? '-' }} (Rp {{ number_format(optional($r->roomType)->price ?? 0, 0, ',', '.') }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label>Check In</label>
        <input type="date" name="check_in" class="w-full p-2" value="{{ old('check_in') }}">
    </div>
    <div class="mb-2">
        <label>Check Out</label>
        <input type="date" name="check_out" class="w-full p-2" value="{{ old('check_out') }}">
    </div>

    <div>
        <button class="bg-green-500 text-white px-4 py-2 rounded">Book Now</button>
    </div>
</form>
@endsection
