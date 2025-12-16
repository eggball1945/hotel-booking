@extends('layouts.app')

@section('title', 'Detail Kamar - ' . ($room->room_number ?? ''))

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            @if($room->foto)
                                <img src="{{ Storage::url($room->foto) }}" class="img-fluid rounded" alt="Kamar {{ $room->room_number }}">
                            @else
                                <img src="{{ asset('images/default-room.jpg') }}" class="img-fluid rounded" alt="Kamar">
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h3 class="fw-bold">{{ optional($room->roomType)->name ?? 'Kamar' }} - No. {{ $room->room_number }}</h3>
                            <p class="text-muted">{{ optional($room->roomType)->description ?? '' }}</p>

                            <h4 class="text-primary">Rp {{ number_format(optional($room->roomType)->price ?? 0, 0, ',', '.') }} / malam</h4>

                            <p class="mt-3">Status: 
                                @if($room->status === 'available')
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($room->status === 'booked')
                                    <span class="badge bg-warning text-dark">Terbooking</span>
                                @else
                                    <span class="badge bg-danger">Maintenance</span>
                                @endif
                            </p>

                            <div class="mt-4">
                                @auth
                                    @if(auth()->user()->role === 'tamu')
                                        <a href="{{ route('tamu.bookings.create', $room->id) }}" class="btn btn-primary me-2">Pesan Sekarang</a>
                                    @elseif(in_array(auth()->user()->role, ['admin','resepsionis']))
                                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-warning me-2">Edit Kamar</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login untuk memesan</a>
                                @endauth

                                <a href="{{ route('tamu.rooms.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold">Informasi Tambahan</h5>
                    <p class="mb-1"><strong>Catatan:</strong></p>
                    <p class="text-muted">{{ $room->note ?? 'Tidak ada catatan' }}</p>
                    <hr>
                    <p class="mb-1"><strong>Dibuat:</strong></p>
                    <p class="text-muted">{{ optional($room->created_at)->format('d/m/Y H:i') ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
