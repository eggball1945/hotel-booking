@extends('layouts.app')

@section('title', 'Pesan Kamar')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i> Form Pemesanan</h4>
                </div>
                
                <div class="card-body">
                    <!-- Informasi Kamar -->
                    <div class="card mb-4 border">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ $room->foto ? Storage::url($room->foto) : asset('images/default-room.jpg') }}" 
                                         class="img-fluid rounded" 
                                         alt="Kamar {{ $room->room_number }}">
                                </div>
                                <div class="col-md-8">
                                    <h5 class="fw-bold">{{ $room->roomType->name ?? 'Kamar' }}</h5>
                                    <p class="text-muted mb-1">No. {{ $room->room_number }}</p>
                                    <p class="mb-2">{{ $room->roomType->description ?? '' }}</p>
                                    <h4 class="text-primary fw-bold">
                                        Rp {{ number_format($room->roomType->price ?? 0, 0, ',', '.') }} /malam
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pemesanan -->
                    <form action="{{ route('tamu.bookings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="check_in" class="form-label fw-bold">
                                    <i class="fas fa-sign-in-alt me-1"></i> Check-in
                                </label>
                                <input type="date" 
                                       class="form-control @error('check_in') is-invalid @enderror" 
                                       id="check_in" 
                                       name="check_in" 
                                       value="{{ old('check_in') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('check_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="check_out" class="form-label fw-bold">
                                    <i class="fas fa-sign-out-alt me-1"></i> Check-out
                                </label>
                                <input type="date" 
                                       class="form-control @error('check_out') is-invalid @enderror" 
                                       id="check_out" 
                                       name="check_out" 
                                       value="{{ old('check_out') }}"
                                       required>
                                @error('check_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="special_notes" class="form-label fw-bold">
                                <i class="fas fa-sticky-note me-1"></i> Catatan Khusus (Opsional)
                            </label>
                            <textarea class="form-control @error('special_notes') is-invalid @enderror" 
                                      id="special_notes" 
                                      name="special_notes" 
                                      rows="3" 
                                      placeholder="Contoh: Permintaan tempat tidur tambahan, alergi makanan, dll.">{{ old('special_notes') }}</textarea>
                            @error('special_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informasi Harga -->
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-info bg-opacity-10">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-calculator me-2"></i> Rincian Harga</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">Harga per malam:</div>
                                    <div class="col-6 text-end">Rp {{ number_format($room->roomType->price ?? 0, 0, ',', '.') }}</div>
                                    
                                    <div class="col-6 mt-2">Jumlah malam:</div>
                                    <div class="col-6 text-end mt-2" id="nightsCount">0</div>
                                    
                                    <hr class="my-2">
                                    
                                    <div class="col-6 fw-bold">Total:</div>
                                    <div class="col-6 text-end fw-bold text-primary" id="totalPrice">Rp 0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tamu -->
                        <div class="mb-4">
                            <h6 class="fw-bold"><i class="fas fa-user me-2"></i> Informasi Tamu</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                                    <p class="mb-0"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('tamu.bookings.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Pemesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const nightsCount = document.getElementById('nightsCount');
    const totalPrice = document.getElementById('totalPrice');
    const pricePerNight = {{ $room->roomType->price ?? 0 }};

    function calculateNights() {
        if (checkIn.value && checkOut.value) {
            const start = new Date(checkIn.value);
            const end = new Date(checkOut.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            nightsCount.textContent = diffDays + ' malam';
            const total = diffDays * pricePerNight;
            totalPrice.textContent = 'Rp ' + total.toLocaleString('id-ID');
        } else {
            nightsCount.textContent = '0';
            totalPrice.textContent = 'Rp 0';
        }
    }

    checkIn.addEventListener('change', function() {
        if (checkOut.value) {
            calculateNights();
        }
    });

    checkOut.addEventListener('change', function() {
        if (checkIn.value) {
            calculateNights();
        }
    });

    // Set check_out min date based on check_in
    checkIn.addEventListener('change', function() {
        if (this.value) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOut.min = nextDay.toISOString().split('T')[0];
            
            // Reset check_out if invalid
            if (checkOut.value && checkOut.value <= this.value) {
                checkOut.value = '';
                calculateNights();
            }
        }
    });

    // Initialize calculation if values exist
    if (checkIn.value && checkOut.value) {
        calculateNights();
    }
});
</script>
@endpush