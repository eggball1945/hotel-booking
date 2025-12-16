@extends('layouts.app')

@section('content')
    <h1>Form Pemesanan</h1>

    <form action="{{ route('tamu.bookings.store') }}" method="POST">
        @csrf
        <label>Pilih Kamar:</label>
        <select name="room_id">
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->room_number }} - {{ optional($room->roomType)->name ?? '-' }}</option>
            @endforeach
        </select>

        <label>Nama Tamu:</label>                   
        <input type="text" name="nama_tamu">

        <label>Tanggal Check-in:</label>
        <input type="date" name="check_in">

        <label>Tanggal Check-out:</label>
        <input type="date" name="check_out">

        <button type="submit">Pesan</button>
    </form>
@endsection
