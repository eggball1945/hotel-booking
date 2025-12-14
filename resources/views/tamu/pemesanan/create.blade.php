@extends('layouts.app')

@section('title', 'Pesan Kamar')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🛏️ Pilih Kamar</h1>
        <p class="text-gray-600">Temukan kamar yang sesuai dengan kebutuhan Anda</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Daftar Kamar -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($rooms as $room)
            <div class="room-card bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                 data-id="{{ $room->id }}"
                 data-nama="{{ $room->roomType->name ?? 'Kamar' }} - {{ $room->room_number }}"
                 data-harga="{{ $room->roomType->price ?? 0 }}"
                 data-deskripsi="{{ $room->roomType->description ?? 'Kamar nyaman' }}"
                 data-image="{{ $room->foto ? Storage::url($room->foto) : ($room->roomType->photo ? Storage::url($room->roomType->photo) : asset('images/default-room.jpg')) }}"
                 data-room-number="{{ $room->room_number }}">
                <!-- Foto Kamar -->
                <div class="relative overflow-hidden h-48">
                    <img src="{{ $room->foto ? Storage::url($room->foto) : ($room->roomType->photo ? Storage::url($room->roomType->photo) : asset('images/default-room.jpg')) }}" 
                         alt="Kamar {{ $room->room_number }}" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @php
                            $statusColors = [
                                'available' => 'bg-green-500',
                                'booked' => 'bg-red-500',
                                'maintenance' => 'bg-yellow-500'
                            ];
                            $statusText = [
                                'available' => 'Tersedia',
                                'booked' => 'Terbooking',
                                'maintenance' => 'Maintenance'
                            ];
                        @endphp
                        <span class="{{ $statusColors[$room->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1 rounded-full">
                            {{ $statusText[$room->status] ?? $room->status }}
                        </span>
                    </div>
                </div>

                <!-- Info Kamar -->
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $room->roomType->name ?? 'Kamar' }}</h3>
                            <p class="text-sm text-gray-500">No. {{ $room->room_number }}</p>
                        </div>
                        @if($room->roomType)
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($room->roomType->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">/malam</p>
                            </div>
                        @endif
                    </div>

                    <p class="text-gray-600 mb-4 line-clamp-2">
                        {{ $room->roomType->description ?? 'Kamar nyaman dengan fasilitas lengkap' }}
                    </p>

                    <!-- Fasilitas (jika ada) -->
                    @if($room->roomType)
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-2">Fasilitas:</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $facilities = [
                                        'AC', 'TV', 'WiFi', 'Kamar Mandi Pribadi',
                                        'Breakfast', 'Room Service', 'Kolam Renang'
                                    ];
                                    $roomFacilities = json_decode($room->roomType->facilities ?? '[]', true) ?: $facilities;
                                @endphp
                                @foreach(array_slice($roomFacilities, 0, 3) as $facility)
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                        {{ $facility }}
                                    </span>
                                @endforeach
                                @if(count($roomFacilities) > 3)
                                    <span class="text-xs text-gray-500">+{{ count($roomFacilities) - 3 }} lagi</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Tombol Pesan -->
                    <button class="btn-book w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                            {{ $room->status !== 'available' ? 'disabled' : '' }}>
                        @if($room->status === 'available')
                            <i class="fas fa-calendar-plus"></i>
                            Pesan Sekarang
                        @elseif($room->status === 'booked')
                            <i class="fas fa-calendar-times"></i>
                            Terbooking
                        @else
                            <i class="fas fa-tools"></i>
                            Maintenance
                        @endif
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Card Detail Pemesanan (Modal Style) -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Form Pemesanan</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>

            <div class="p-6">
                <!-- Room Info -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="md:w-1/3">
                            <img id="detailImage" src="" alt="Foto Kamar" class="w-full h-48 object-cover rounded-lg shadow">
                        </div>
                        <div class="md:w-2/3">
                            <h4 id="detailNama" class="text-2xl font-bold text-gray-800 mb-2"></h4>
                            <p class="text-gray-600 mb-3" id="detailDeskripsi"></p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Harga per Malam</p>
                                    <p class="text-xl font-bold text-blue-600">Rp <span id="detailHarga"></span></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Nomor Kamar</p>
                                    <p class="text-xl font-semibold text-gray-800" id="detailRoomNumber"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <form action="{{ route('tamu.bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="room_id" id="detailRoomId">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-check mr-2"></i>Check-in
                            </label>
                            <input type="date" name="check_in" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-times mr-2"></i>Check-out
                            </label>
                            <input type="date" name="check_out" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h5 class="font-semibold text-gray-700 mb-3">Informasi Tamu</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                                <input type="text" name="guest_name" class="w-full border border-gray-300 rounded-lg px-4 py-2" required value="{{ auth()->user()->name ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Email</label>
                                <input type="email" name="guest_email" class="w-full border border-gray-300 rounded-lg px-4 py-2" required value="{{ auth()->user()->email ?? '' }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600 mb-1">Catatan Khusus (Opsional)</label>
                                <textarea name="special_notes" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Permintaan khusus..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Konfirmasi Pesanan
                        </button>
                        <button type="button" id="cancelBooking" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.room-card {
    transition: all 0.3s ease;
}

.room-card:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

#bookingModal {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingModal = document.getElementById('bookingModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBooking = document.getElementById('cancelBooking');
    
    document.querySelectorAll('.btn-book').forEach(btn => {
        btn.addEventListener('click', function() {
            // Cek apakah kamar tersedia
            if (this.disabled) {
                return;
            }
            
            const card = this.closest('.room-card');
            
            // Ambil data kamar
            const id = card.dataset.id;
            const nama = card.dataset.nama;
            const harga = new Intl.NumberFormat('id-ID').format(card.dataset.harga);
            const deskripsi = card.dataset.deskripsi;
            const image = card.dataset.image;
            const roomNumber = card.dataset.roomNumber;
            
            // Set detail form
            document.getElementById('detailNama').innerText = nama;
            document.getElementById('detailHarga').innerText = harga;
            document.getElementById('detailDeskripsi').innerText = deskripsi;
            document.getElementById('detailImage').src = image;
            document.getElementById('detailRoomId').value = id;
            document.getElementById('detailRoomNumber').innerText = roomNumber;
            
            // Set tanggal minimal untuk check-out
            const checkInInput = document.querySelector('input[name="check_in"]');
            const checkOutInput = document.querySelector('input[name="check_out"]');
            
            checkInInput.addEventListener('change', function() {
                checkOutInput.min = this.value;
                if (checkOutInput.value && checkOutInput.value < this.value) {
                    checkOutInput.value = '';
                }
            });
            
            // Tampilkan modal
            bookingModal.classList.remove('hidden');
            bookingModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Fungsi tutup modal
    function closeBookingModal() {
        bookingModal.classList.remove('flex');
        bookingModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    closeModal.addEventListener('click', closeBookingModal);
    cancelBooking.addEventListener('click', closeBookingModal);
    
    // Tutup modal dengan klik di luar
    bookingModal.addEventListener('click', function(e) {
        if (e.target === bookingModal) {
            closeBookingModal();
        }
    });
    
    // Validasi form
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const checkIn = this.querySelector('input[name="check_in"]').value;
        const checkOut = this.querySelector('input[name="check_out"]').value;
        
        if (!checkIn || !checkOut) {
            e.preventDefault();
            alert('Silakan isi tanggal check-in dan check-out');
            return;
        }
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            e.preventDefault();
            alert('Tanggal check-out harus setelah tanggal check-in');
            return;
        }
        
        // Konfirmasi sebelum submit
        if (!confirm('Apakah Anda yakin dengan pemesanan ini?')) {
            e.preventDefault();
        }
    });
});
</script>

@if($rooms->isEmpty())
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-door-closed text-5xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada kamar tersedia</h3>
        <p class="text-gray-500">Silakan coba lagi nanti</p>
    </div>
@endif
@endsection