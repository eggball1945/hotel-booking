@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3>Riwayat Pemesanan</h3>
    <hr>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Kamar</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Tanggal Pesan</th>
            </tr>
        </thead>

        <tbody>
            @forelse($bookings as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $b->room->nama }}</td>
                <td>{{ $b->check_in }}</td>
                <td>{{ $b->check_out }}</td>
                <td>{{ $b->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">
                    Belum ada pemesanan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
