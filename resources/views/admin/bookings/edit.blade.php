@extends('layouts.app')

@section('content')
<div class="bg-white shadow-xl rounded-xl p-6 max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit Booking</h2>
        <a href="{{ route('admin.bookings.index') }}"
           class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.bookings.update',$booking) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Kode Booking
            </label>
            <input type="text"
                   value="{{ $booking->booking_code }}"
                   disabled
                   class="w-full border rounded-lg px-3 py-2 bg-gray-100">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Status Booking
            </label>
            <select name="status"
                    class="w-full border rounded-lg px-3 py-2">
                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>
                    Confirmed
                </option>
                <option value="checked_in" {{ $booking->status == 'checked_in' ? 'selected' : '' }}>
                    Check In
                </option>
                <option value="checked_out" {{ $booking->status == 'checked_out' ? 'selected' : '' }}>
                    Check Out
                </option>
                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>
            @error('status')
                <small class="text-red-500">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Catatan Khusus
            </label>
            <textarea name="special_notes"
                      rows="3"
                      class="w-full border rounded-lg px-3 py-2">{{ old('special_notes',$booking->special_notes) }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
