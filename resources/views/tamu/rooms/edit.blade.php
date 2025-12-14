@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Edit Room {{ $room->room_number }}</h1>

<form action="{{ route('admin.rooms.update', $room) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-2">
        <label>Room Number</label>
        <input type="text" name="room_number" class="w-full p-2" value="{{ old('room_number',$room->room_number) }}">
    </div>
    <div class="mb-2">
        <label>Room Type</label>
        <select name="room_type_id" class="w-full p-2">
            @foreach($types as $t)
                <option value="{{ $t->id }}" {{ $room->room_type_id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label>Status</label>
        <select name="status" class="w-full p-2">
            <option value="available" {{ $room->status=='available' ? 'selected':'' }}>available</option>
            <option value="booked" {{ $room->status=='booked' ? 'selected':'' }}>booked</option>
            <option value="maintenance" {{ $room->status=='maintenance' ? 'selected':'' }}>maintenance</option>
        </select>
    </div>
    <div>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </div>
</form>
@endsection
