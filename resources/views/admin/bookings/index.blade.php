@extends('layouts.app')

@section('content')
<div class="bg-white shadow-xl rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen Booking</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Kamar</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold"a
                        {{ $booking->booking_code }}
                    </td>
                    <td class="px-4 py-3">{{ $booking->user->name }}</td>
                    <td class="px-4 py-3">
                        {{ $booking->room->roomType->name }} <br>
                        <small>No {{ $booking->room->room_number }}</small>
                    </td>
                    <td class="px-4 py-3">
                        {{ $booking->check_in->format('d M') }} -
                        {{ $booking->check_out->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-blue-600">
                        Rp {{ number_format($booking->total_price,0,',','.') }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs bg-gray-200">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.bookings.show',$booking) }}" class="text-blue-600 mx-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.bookings.edit',$booking) }}" class="text-yellow-600 mx-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.bookings.destroy',$booking) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus booking?')" class="text-red-600 mx-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
