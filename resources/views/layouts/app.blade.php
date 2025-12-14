<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title','Dashboard') - Hotel Booking</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-light">

{{-- ================= NAVBAR ================= --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">
            <i class="fas fa-hotel me-2"></i>Hotel Booking
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">

                @auth
                    {{-- USER NAME --}}
                    <span class="nav-item nav-link text-dark fw-semibold me-2">
                        <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                    </span>

                    {{-- ADMIN / RESEPSIONIS --}}
                    @if(in_array(auth()->user()->role, ['admin','resepsionis']))
                        <a class="nav-item nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>

                        <a class="nav-item nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                           href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users me-1"></i>Users
                        </a>

                        <a class="nav-item nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}"
                           href="{{ route('admin.rooms.index') }}">
                            <i class="fas fa-bed me-1"></i>Rooms
                        </a>

                        <a class="nav-item nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
                           href="{{ route('admin.bookings.index') }}">
                            <i class="fas fa-calendar-check me-1"></i>Bookings
                        </a>

                        <form method="POST" action="{{ route('admin.logout') }}" class="ms-2">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>

                    {{-- TAMU --}}
                    @elseif(auth()->user()->role === 'tamu')
                        <a class="nav-item nav-link {{ request()->routeIs('tamu.dashboard') ? 'active' : '' }}"
                           href="{{ route('tamu.dashboard') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>

                        <a class="nav-item nav-link {{ request()->routeIs('tamu.rooms.*') ? 'active' : '' }}"
                           href="{{ route('tamu.rooms.index') }}">
                            <i class="fas fa-bed me-1"></i>Kamar
                        </a>

                                <a class="nav-item nav-link {{ request()->routeIs('tamu.bookings.history') ? 'active' : '' }}"
                                    href="{{ route('tamu.bookings.history') }}">
                            <i class="fas fa-history me-1"></i>Riwayat
                        </a>

                        <a class="nav-item nav-link {{ request()->routeIs('tamu.profile') ? 'active' : '' }}"
                           href="{{ route('tamu.profile') }}">
                            <i class="fas fa-user me-1"></i>Profil
                        </a>

                        <form method="POST" action="{{ route('tamu.logout') }}" class="ms-2">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    @endif

                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ================= FLASH MESSAGE ================= --}}
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

{{-- ================= CONTENT ================= --}}
<main class="container mt-4">
    @yield('content')
</main>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
