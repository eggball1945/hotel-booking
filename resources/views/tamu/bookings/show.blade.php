@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-file-invoice me-2"></i> Detail Pemesanan</h4>
                    <span class="badge bg-light text-dark fs-6">
                        @if($booking->booking_code)
                            Kode: {{ $booking->booking_code }}
                        @else
                            ID: {{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                        @endif
                    </span>
                </div>
                
                <div class="card-body">
                    <!-- Status Banner -->
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'checked_in' => 'primary',
                            'checked_out' => 'info',
                            'cancelled' => 'danger'
                        ];
                        $statusText = [
                            'pending' => 'Menunggu Konfirmasi',
                            'confirmed' => 'Terkonfirmasi',
                            'checked_in' => 'Check-in',
                            'checked_out' => 'Check-out',
                            'cancelled' => 'Dibatalkan'
                        ];
                    @endphp
                    <div class="alert alert-{{ $statusColors[$booking->status] ?? 'warning' }} d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Status: {{ $statusText[$booking->status] ?? $booking->status }}</h5>
                            <p class="mb-0">
                                @if($booking->status == 'pending')
                                    Pemesanan Anda sedang menunggu konfirmasi dari admin.
                                @elseif($booking->status == 'confirmed')
                                    Pemesanan Anda sudah dikonfirmasi. Silakan lakukan check-in sesuai tanggal.
                                @elseif($booking->status == 'checked_in')
                                    Anda sedang menginap. Check-out pada {{ $booking->check_out ? $booking->check_out->format('d M Y') : '-' }}.
                                @elseif($booking->status == 'checked_out')
                                    Terima kasih telah menginap di hotel kami.
                                @else
                                    Pemesanan ini telah dibatalkan.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Informasi Kamar -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-bed me-2"></i> Informasi Kamar</h6>
                                </div>
                                <div class="card-body">
                                    @if($booking->room)
                                        <div class="row">
                                            <div class="col-5">
                                                @php
                                                    $roomImage = $booking->room->foto ?? 
                                                                ($booking->room->roomType->images ?? 'images/default-room.jpg');
                                                @endphp
                                                <img src="{{ Storage::exists('public/' . $roomImage) ? Storage::url($roomImage) : asset($roomImage) }}" 
                                                     class="img-fluid rounded" 
                                                     alt="Kamar"
                                                     style="height: 120px; width: 100%; object-fit: cover;">
                                            </div>
                                            <div class="col-7">
                                                <h5 class="fw-bold">
                                                    {{ $booking->room->roomType->name ?? 'Kamar' }}
                                                </h5>
                                                <p class="mb-1"><strong>No. Kamar:</strong> {{ $booking->room->room_number ?? '-' }}</p>
                                                <p class="mb-1"><strong>Tipe:</strong> {{ $booking->room->roomType->name ?? 'Standard' }}</p>
                                                <p class="mb-0"><strong>Kapasitas:</strong> {{ $booking->room->roomType->capacity ?? 2 }} orang</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p class="mb-0"><strong>Deskripsi:</strong><br>
                                            {{ $booking->room->roomType->description ?? 'Kamar nyaman dengan fasilitas lengkap' }}
                                        </p>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Informasi kamar tidak tersedia.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pemesanan -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Informasi Pemesanan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Check-in:</strong></p>
                                            <h5 class="text-primary">
                                                {{ $booking->check_in ? $booking->check_in->format('d M Y') : '-' }}
                                            </h5>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Check-out:</strong></p>
                                            <h5 class="text-primary">
                                                {{ $booking->check_out ? $booking->check_out->format('d M Y') : '-' }}
                                            </h5>
                                        </div>
                                    </div>
                                    
                                    <p class="mb-2"><strong>Durasi:</strong> {{ $booking->total_nights ?? 0 }} malam</p>
                                    <p class="mb-2"><strong>Tanggal Pesan:</strong> 
                                        {{ $booking->created_at ? $booking->created_at->format('d M Y H:i') : '-' }}
                                    </p>
                                    
                                    @if($booking->special_notes)
                                    <div class="alert alert-info mt-3">
                                        <strong><i class="fas fa-sticky-note me-1"></i> Catatan Khusus:</strong>
                                        <p class="mb-0 mt-1">{{ $booking->special_notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Pembayaran -->
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-receipt me-2"></i> Rincian Pembayaran</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Harga per malam</td>
                                            <td class="text-end">
                                                Rp {{ number_format($booking->room->roomType->price ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah malam</td>
                                            <td class="text-end">{{ $booking->total_nights ?? 0 }} malam</td>
                                        </tr>
                                        <tr>
                                            <td>Subtotal</td>
                                            <td class="text-end">
                                                Rp {{ number_format(($booking->room->roomType->price ?? 0) * ($booking->total_nights ?? 0), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pajak & Layanan (10%)</td>
                                            <td class="text-end">
                                                Rp {{ number_format(($booking->total_price ?? 0) * 0.1, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr class="table-active">
                                            <td><strong>TOTAL</strong></td>
                                            <td class="text-end fw-bold text-primary fs-5">
                                                Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    @if($booking->status == 'pending')
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Pembayaran:</strong> Silakan lakukan pembayaran saat check-in di hotel.
                                    </div>
                                    @endif
                                    
                                    <!-- Bukti Pembayaran -->
                                    @if($booking->payment_proof)
                                    <div class="mt-4">
                                        <h6 class="mb-2"><i class="fas fa-file-invoice-dollar me-2"></i> Bukti Pembayaran</h6>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ Storage::url('public/payments/' . $booking->payment_proof) }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm me-2">
                                                <i class="fas fa-eye me-1"></i> Lihat Bukti
                                            </a>
                                            <span class="text-muted">
                                                {{ $booking->payment_proof }}
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tamu -->
                    <div class="card border mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-user me-2"></i> Informasi Tamu</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nama:</strong> {{ $booking->user->name ?? 'Tidak diketahui' }}</p>
                                    <p><strong>Email:</strong> {{ $booking->user->email ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Tanggal Pendaftaran:</strong> 
                                        {{ $booking->user->created_at ? $booking->user->created_at->format('d M Y') : '-' }}
                                    </p>
                                    <p><strong>Total Pemesanan:</strong> 
                                        {{ $booking->user && $booking->user->bookings ? $booking->user->bookings->count() : 0 }} kali
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('tamu.bookings.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
                        </a>
                        
                        @if($booking->status == 'pending')
                        <form action="{{ route('tamu.bookings.cancel', $booking->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin membatalkan pemesanan ini?')">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-1"></i> Batalkan Pemesanan
                            </button>
                        </form>
                        @endif
                        
                        @if($booking->status == 'confirmed')
                        <a href="#" class="btn btn-success" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Cetak Invoice
                        </a>
                        @endif
                    </div>
                </div>
                
                <!-- Footer dengan info booking code -->
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Simpan kode booking Anda: 
                                <strong>{{ $booking->booking_code ?? 'Tidak tersedia' }}</strong>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                Terakhir diupdate: {{ $booking->updated_at ? $booking->updated_at->format('d M Y H:i') : '-' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        .card-header.bg-primary {
            background-color: #0d6efd !important;
            -webkit-print-color-adjust: exact;
        }
    }
    
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .table-borderless td {
        padding: 8px 0;
    }
</style>
@endpush