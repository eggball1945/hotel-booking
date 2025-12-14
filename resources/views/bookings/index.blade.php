@extends('layouts.app')

@section('content')
<h1 class="text-2xl mb-4">Bookings</h1>

@if(auth()->user()->role === 'tamu')
    <a href="{{ route('bookings.create') }}" class="bg-blue-500 text-white px-3 py-2 rounded mb-4 inline-block">New Booking</a>
@endif

<table class="w-full bg-white rounded shadow">
    <thead>
        <tr class="border-b">
            <th class="p-2">Code</th>
            <th class="p-2">Room</th>
            <th class="p-2">Guest</th>
            <th class="p-2">Check In</th>
            <th class="p-2">Check Out</th>
            <th class="p-2">Total</th>
            <th class="p-2">Status</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $b)
        <tr class="border-b">
            <td class="p-2">{{ $b->booking_code }}</td>
            <td class="p-2">{{ $b->room->room_number }} ({{ $b->room->type->name }})</td>
            <td class="p-2">{{ $b->user->name }}</td>
            <td class="p-2">{{ $b->check_in }}</td>
            <td class="p-2">{{ $b->check_out }}</td>
            <td class="p-2">{{ $b->total_price }}</td>
            <td class="p-2">{{ $b->status }}</td>
            <td class="p-2">
                <a href="{{ route('bookings.show', $b) }}">View</a>
                @if(auth()->user()->role !== 'tamu')
                    <a href="{{ route('bookings.edit', $b) }}" class="ml-2">Manage</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
