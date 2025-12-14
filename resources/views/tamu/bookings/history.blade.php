@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@section('content')
<style>
    .status-badge {
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 20px;
    }
    .booking-card {
        border-left: 4px solid;
        transition: all 0.3s;
    }
    .booking-card:hover {
        transform: translateX(5px);
    }
</style>

<div class="container">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold mb-1">
                    <i class="fas fa-history me-2"></i>Riwayat Pemesanan
                </h1>
                <p class="text-muted mb-0">Lihat semua pemesanan kamar Anda</p>
            </div>
            <a href="{{ route('tamu.bookings.index') }}" class="btn btn-primary">
                <i class="fas fa-bed me-2"></i>Pesan Kamar
            </a>
        </div>
    </div>

    {{-- Statistik --}}
    @php
        $totalBookings = $bookings->total();
        $pendingBookings = $bookings->where('status', 'pending')->count();
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();
        $activeBookings = $pendingBookings + $confirmedBookings;
    @endphp

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total</h6>
                    <h3>{{ $totalBookings }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Menunggu</h6>
                    <h3>{{ $pendingBookings }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Terkonfirmasi</h6>
                    <h3>{{ $confirmedBookings }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Aktif</h6>
                    <h3>{{ $activeBookings }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($bookings->count())
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kamar</th>
                            <th>Tanggal</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $loop->iteration + ($bookings->currentPage()-1)*$bookings->perPage() }}</td>
                            <td>
                                <strong>{{ $booking->room->roomType->name ?? 'Kamar' }}</strong><br>
                                <small>No {{ $booking->room->room_number }}</small>
                            </td>
                            <td>
                                {{ $booking->check_in->format('d M Y') }}<br>
                                → {{ $booking->check_out->format('d M Y') }}
                            </td>
                            <td>{{ $booking->total_nights }} malam</td>
                            <td class="fw-bold text-primary">
                                Rp {{ number_format($booking->total_price,0,',','.') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $booking->status_color }}">
                                    {{ $booking->status_text }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('tamu.bookings.show',$booking->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($booking->status === 'pending')
                                <form action="{{ route('tamu.bookings.cancel',$booking->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Batalkan booking?')">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center p-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>Belum ada pemesanan</h5>
                </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $bookings->links() }}
    </div>

</div>
@endsection
