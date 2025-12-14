@extends('layouts.app')

@section('title', 'Dashboard Tamu')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h1 class="fw-bold mb-3">
                    Selamat Datang, {{ auth()->user()->name }}! 👋
                </h1>

                <p class="text-muted mb-4">
                    Selamat menikmati layanan pemesanan kamar hotel kami
                </p>

                <div class="row mt-4">
                    <!-- Pesan Kamar -->
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <i class="fas fa-bed fa-2x text-primary mb-3"></i>
                                <h5>Pesan Kamar</h5>
                                <a href="{{ route('tamu.rooms.index') }}"
                                   class="btn btn-primary mt-2">
                                    Lihat Kamar Tersedia
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat -->
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <i class="fas fa-history fa-2x text-success mb-3"></i>
                                <h5>Riwayat Pemesanan</h5>
                                <a href="{{ route('tamu.bookings.history') }}"
                                   class="btn btn-success mt-2">
                                    Lihat Riwayat
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profil -->
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <i class="fas fa-user fa-2x text-info mb-3"></i>
                                <h5>Profil Saya</h5>
                                <a href="{{ route('tamu.profile') }}"
                                   class="btn btn-info mt-2">
                                    Kelola Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
