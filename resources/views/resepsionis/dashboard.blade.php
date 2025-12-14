@extends('layouts.resepsionis')

@section('title', 'Dashboard Resepsionis')
@section('page-title', 'Dashboard Resepsionis')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Rooms -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-50 rounded-lg mr-4">
                <i class="fas fa-door-open text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Kamar</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_rooms'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Available Rooms -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-50 rounded-lg mr-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Kamar Tersedia</p>
                <h3 class="text-2xl font-bold text-green-600">{{ $stats['available_rooms'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Today Check-ins -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-50 rounded-lg mr-4">
                <i class="fas fa-sign-in-alt text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Check-in Hari Ini</p>
                <h3 class="text-2xl font-bold text-purple-600">{{ $stats['today_checkins'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Current Guests -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-50 rounded-lg mr-4">
                <i class="fas fa-users text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tamu Menginap</p>
                <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['current_guests'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Today's Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Today's Arrivals -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-sign-in-alt text-green-600 mr-2"></i>
                Check-in Hari Ini
                <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">{{ $todayArrivals->count() }}</span>
            </h3>
        </div>
        <div class="p-6">
            @if($todayArrivals->count() > 0)
            <div class="space-y-4">
                @foreach($todayArrivals as $arrival)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                    <div>
                        <p class="font-medium text-gray-900">{{ $arrival->user->name }}</p>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <i class="fas fa-bed mr-2"></i>
                            <span>Kamar {{ $arrival->room->room_number }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-clock mr-1"></i>
                            <span>{{ Carbon\Carbon::parse($arrival->check_in)->format('H:i') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($arrival->status == 'checked_in') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $arrival->status == 'checked_in' ? 'Sudah Check-in' : 'Menunggu' }}
                        </span>
                        @if($arrival->status == 'confirmed')
                        <form action="{{ route('resepsionis.bookings.checkin', $arrival) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-check text-3xl mb-3"></i>
                <p>Tidak ada check-in hari ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Today's Departures -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-sign-out-alt text-red-600 mr-2"></i>
                Check-out Hari Ini
                <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">{{ $todayDepartures->count() }}</span>
            </h3>
        </div>
        <div class="p-6">
            @if($todayDepartures->count() > 0)
            <div class="space-y-4">
                @foreach($todayDepartures as $departure)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                    <div>
                        <p class="font-medium text-gray-900">{{ $departure->user->name }}</p>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <i class="fas fa-bed mr-2"></i>
                            <span>Kamar {{ $departure->room->room_number }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-clock mr-1"></i>
                            <span>{{ Carbon\Carbon::parse($departure->check_out)->format('H:i') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($departure->status == 'checked_out') bg-gray-100 text-gray-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ $departure->status == 'checked_out' ? 'Sudah Check-out' : 'Belum Check-out' }}
                        </span>
                        @if($departure->status == 'checked_in')
                        <a href="{{ route('resepsionis.bookings.show', $departure) }}" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-money-check-alt"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                <p>Tidak ada check-out hari ini</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Current Guests -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-users text-blue-600 mr-2"></i>
                Tamu Sedang Menginap
                <span class="ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $currentGuests->count() }}</span>
            </h3>
            <a href="{{ route('resepsionis.guests.current') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Lihat semua →
            </a>
        </div>
    </div>
    <div class="p-6">
        @if($currentGuests->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($currentGuests as $guest)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-medium text-gray-900">{{ $guest->user->name }}</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-bed mr-1"></i> Kamar {{ $guest->room->room_number }}
                        </p>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <span>Check-in: {{ Carbon\Carbon::parse($guest->check_in)->format('d/m') }}</span>
                            <span class="mx-1">-</span>
                            <span>Check-out: {{ Carbon\Carbon::parse($guest->check_out)->format('d/m') }}</span>
                        </div>
                        <div class="mt-3 flex justify-between">
                            <span class="text-xs text-gray-500">
                                Kode: <strong>{{ $guest->booking_code }}</strong>
                            </span>
                            <a href="{{ route('resepsionis.bookings.show', $guest) }}" 
                               class="text-xs text-blue-600 hover:text-blue-800">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-user-slash text-3xl mb-3"></i>
            <p>Tidak ada tamu yang sedang menginap</p>
        </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('resepsionis.bookings.today') }}" 
           class="bg-white rounded-lg shadow p-4 hover:shadow-md transition flex items-center">
            <div class="p-3 bg-blue-50 rounded-lg mr-4">
                <i class="fas fa-calendar-day text-blue-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900">Hari Ini</p>
                <p class="text-sm text-gray-500">Lihat aktivitas hari ini</p>
            </div>
        </a>
        
        <a href="{{ route('resepsionis.rooms.index') }}" 
           class="bg-white rounded-lg shadow p-4 hover:shadow-md transition flex items-center">
            <div class="p-3 bg-green-50 rounded-lg mr-4">
                <i class="fas fa-bed text-green-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900">Daftar Kamar</p>
                <p class="text-sm text-gray-500">Lihat status kamar</p>
            </div>
        </a>
        
        <a href="{{ route('resepsionis.bookings.index') }}" 
           class="bg-white rounded-lg shadow p-4 hover:shadow-md transition flex items-center">
            <div class="p-3 bg-yellow-50 rounded-lg mr-4">
                <i class="fas fa-calendar-check text-yellow-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900">Semua Booking</p>
                <p class="text-sm text-gray-500">Kelola pemesanan</p>
            </div>
        </a>
        
        <a href="{{ route('resepsionis.reports.daily') }}" 
           class="bg-white rounded-lg shadow p-4 hover:shadow-md transition flex items-center">
            <div class="p-3 bg-purple-50 rounded-lg mr-4">
                <i class="fas fa-chart-line text-purple-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900">Laporan</p>
                <p class="text-sm text-gray-500">Laporan harian</p>
            </div>
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }
</style>
@endpush