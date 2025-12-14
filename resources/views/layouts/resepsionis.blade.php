<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resepsionis - @yield('title', 'Hotel Management System')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-concierge-bell text-blue-600 text-2xl mr-3"></i>
                        <span class="font-bold text-xl text-gray-800">Hotel Front Desk</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('resepsionis.dashboard') }}" 
                           class="{{ request()->routeIs('resepsionis.dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('resepsionis.bookings.index') }}" 
                           class="{{ request()->routeIs('resepsionis.bookings.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-calendar-check mr-2"></i> Bookings
                        </a>
                        <a href="{{ route('resepsionis.bookings.today') }}" 
                           class="{{ request()->routeIs('resepsionis.bookings.today') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-calendar-day mr-2"></i> Hari Ini
                        </a>
                        <a href="{{ route('resepsionis.rooms.index') }}" 
                           class="{{ request()->routeIs('resepsionis.rooms.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-bed mr-2"></i> Kamar
                        </a>
                        <a href="{{ route('resepsionis.guests.current') }}" 
                           class="{{ request()->routeIs('resepsionis.guests.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-users mr-2"></i> Tamu
                        </a>
                        <div class="relative group">
                            <button class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fas fa-chart-bar mr-2"></i> Laporan
                            </button>
                            <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-1 w-48 z-10">
                                <a href="{{ route('resepsionis.reports.daily') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Harian</a>
                                <a href="{{ route('resepsionis.reports.occupancy') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Occupancy</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <!-- Quick Actions -->
                    <div class="flex space-x-2 mr-4">
                        <!-- Quick Check-in Form -->
                        <div class="relative group">
                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-sm font-medium flex items-center">
                                <i class="fas fa-sign-in-alt mr-1"></i> Quick Check-in
                            </button>
                            <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-1 p-4 w-64 right-0 z-10">
                                <h4 class="font-medium text-gray-800 mb-2">Quick Check-in</h4>
                                <input type="text" id="quickCheckinCode" placeholder="Masukkan kode booking" 
                                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm mb-2">
                                <button onclick="quickCheckIn()" class="w-full bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded text-sm">
                                    Proses Check-in
                                </button>
                            </div>
                        </div>
                        
                        <!-- Quick Check-out Form -->
                        <div class="relative group">
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-sm font-medium flex items-center">
                                <i class="fas fa-sign-out-alt mr-1"></i> Quick Check-out
                            </button>
                            <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-1 p-4 w-64 right-0 z-10">
                                <h4 class="font-medium text-gray-800 mb-2">Quick Check-out</h4>
                                <input type="text" id="quickCheckoutCode" placeholder="Masukkan kode booking" 
                                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm mb-2">
                                <select id="quickPaymentMethod" class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm mb-2">
                                    <option value="cash">Cash</option>
                                    <option value="debit_card">Debit Card</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                                <button onclick="quickCheckOut()" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm">
                                    Proses Check-out
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-3">{{ auth()->user()->name }}</span>
                        <div class="relative group">
                            <button class="flex items-center text-sm focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                            </button>
                            <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-1 w-48 right-0 z-10">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-600 mt-1">@yield('page-subtitle', 'Sistem manajemen hotel')</p>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-hotel mr-1"></i> Hotel Management System v1.0
                    </p>
                </div>
                <div class="text-sm text-gray-500">
                    {{ now()->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Initialize datepickers
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
            });
        });

        // Quick Check-in
        async function quickCheckIn() {
            const code = document.getElementById('quickCheckinCode').value;
            
            if (!code) {
                alert('Masukkan kode booking terlebih dahulu');
                return;
            }

            try {
                const response = await fetch('{{ route("resepsionis.quick.checkin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ booking_code: code })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    document.getElementById('quickCheckinCode').value = '';
                    location.reload();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        // Quick Check-out
        async function quickCheckOut() {
            const code = document.getElementById('quickCheckoutCode').value;
            const method = document.getElementById('quickPaymentMethod').value;
            
            if (!code) {
                alert('Masukkan kode booking terlebih dahulu');
                return;
            }

            if (!method) {
                alert('Pilih metode pembayaran');
                return;
            }

            try {
                const response = await fetch('{{ route("resepsionis.quick.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        booking_code: code,
                        payment_method: method 
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    document.getElementById('quickCheckoutCode').value = '';
                    location.reload();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }
    </script>

    @stack('scripts')
</body>
</html>