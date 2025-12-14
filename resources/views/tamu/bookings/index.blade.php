@extends('layouts.app')

@section('title', 'Daftar Kamar Tersedia')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">🛏️ Kamar Tersedia</h1>
            <p class="text-muted">Pilih kamar yang sesuai dengan kebutuhan Anda</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        @forelse($rooms as $room)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($room->roomType && $room->roomType->images)
                    <img src="{{ Storage::exists('public/' . $room->roomType->images) ? Storage::url($room->roomType->images) : asset('images/default-room.jpg') }}" 
                         class="card-img-top" 
                         alt="Kamar {{ $room->room_number }}"
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-bed fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $room->roomType->name ?? 'Kamar' }}</h5>
                    <h6 class="text-muted mb-3">No. {{ $room->room_number }}</h6>
                    
                    <p class="card-text">
                        <i class="fas fa-bed me-2"></i> {{ $room->roomType->name ?? 'Standard' }}
                    </p>
                    
                    <p class="card-text">
                        <i class="fas fa-user me-2"></i> Kapasitas: {{ $room->roomType->capacity ?? 2 }} orang
                    </p>
                    
                    <div class="mb-3">
                        <span class="h4 fw-bold text-primary">
                            Rp {{ number_format($room->roomType->price ?? 0, 0, ',', '.') }}
                        </span>
                        <span class="text-muted">/malam</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('tamu.bookings.create', $room->id) }}" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-check me-2"></i> Pesan Sekarang
                        </a>
                        <button type="button" class="btn btn-outline-secondary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#detailModal{{ $room->id }}">
                            <i class="fas fa-info-circle me-1"></i> Detail Kamar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail -->
        <div class="modal fade" id="detailModal{{ $room->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $room->roomType->name ?? 'Kamar' }} - No. {{ $room->room_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if($room->roomType && $room->roomType->images)
                                    <img src="{{ Storage::exists('public/' . $room->roomType->images) ? Storage::url($room->roomType->images) : asset('images/default-room.jpg') }}" 
                                         class="img-fluid rounded mb-3" 
                                         style="width: 100%; height: 250px; object-fit: cover;">
                                @else
                                    <div class="bg-light p-5 rounded mb-3 text-center">
                                        <i class="fas fa-bed fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h4 class="text-primary fw-bold">
                                        Rp {{ number_format($room->roomType->price ?? 0, 0, ',', '.') }}
                                        <small class="text-muted fs-6">/malam</small>
                                    </h4>
                                </div>
                                
                                <div class="mb-3">
                                    <h6><strong>Informasi Kamar:</strong></h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-hashtag me-2 text-muted"></i> No. Kamar: {{ $room->room_number }}</li>
                                        <li><i class="fas fa-bed me-2 text-muted"></i> Tipe: {{ $room->roomType->name ?? 'Standard' }}</li>
                                        <li><i class="fas fa-user me-2 text-muted"></i> Kapasitas: {{ $room->roomType->capacity ?? 2 }} orang</li>
                                        <li><i class="fas fa-layer-group me-2 text-muted"></i> Status: 
                                            <span class="badge bg-{{ $room->status == 'available' ? 'success' : 'danger' }}">
                                                {{ $room->status == 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                
                                @if($room->roomType && $room->roomType->description)
                                <div class="mb-3">
                                    <h6><strong>Deskripsi:</strong></h6>
                                    <p class="text-muted">{{ $room->roomType->description }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($room->roomType && $room->roomType->facilities)
                        <div class="mt-3">
                            <h6><strong>Fasilitas:</strong></h6>
                            <div class="row">
                                @php
                                    $facilities = explode(',', $room->roomType->facilities);
                                @endphp
                                @foreach($facilities as $facility)
                                <div class="col-md-6 mb-2">
                                    <i class="fas fa-check text-success me-2"></i> {{ trim($facility) }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ route('tamu.bookings.create', $room->id) }}" class="btn btn-primary">
                            <i class="fas fa-calendar-check me-1"></i> Pesan Kamar Ini
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-bed fa-3x mb-3 text-muted"></i>
                <h4>Tidak ada kamar tersedia</h4>
                <p class="text-muted">Silakan coba lagi nanti</p>
                <a href="{{ route('tamu.dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($rooms->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                @if($rooms->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $rooms->previousPageUrl() }}">Previous</a></li>
                @endif

                @for($i = 1; $i <= $rooms->lastPage(); $i++)
                    <li class="page-item {{ $rooms->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $rooms->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if($rooms->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $rooms->nextPageUrl() }}">Next</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-img-top {
        border-radius: 10px 10px 0 0;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        padding: 10px 20px;
        font-weight: 500;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    
    .btn-outline-secondary {
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .pagination .page-link {
        color: #0d6efd;
        border-radius: 5px;
        margin: 0 3px;
    }
    
    .pagination .page-link:hover {
        color: #0a58ca;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .modal-content {
        border-radius: 10px;
        border: none;
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-radius: 10px 10px 0 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal otomatis jika ada parameter room di URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('room')) {
            const roomId = urlParams.get('room');
            const modalElement = document.getElementById('detailModal' + roomId);
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }
        
        // Animasi untuk card
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush