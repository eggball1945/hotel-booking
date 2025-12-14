<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTamuController;
use App\Http\Controllers\ResepsionisController;

use App\Http\Controllers\Tamu\BookingController as TamuBookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin', 'resepsionis' => redirect()->route('admin.dashboard'),
            'tamu' => redirect()->route('tamu.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| GUEST (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // TAMU
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // ADMIN / RESEPSIONIS
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| TAMU
|--------------------------------------------------------------------------
*/
Route::prefix('tamu')
    ->name('tamu.')
    ->middleware(['auth', 'tamu'])
    ->group(function () {

    Route::get('/dashboard', fn () => view('tamu.dashboard'))->name('dashboard');

    // PROFILE
    Route::get('/profile', fn () => view('tamu.profile'))->name('profile'); // ✅ ubah jadi tamu.profile
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | BOOKINGS TAMU
    |--------------------------------------------------------------------------
    */
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [TamuBookingController::class, 'index'])->name('index');
        Route::get('/history', [TamuBookingController::class, 'history'])->name('history');
        Route::get('/create/{room}', [TamuBookingController::class, 'create'])->name('create');
        Route::post('/', [TamuBookingController::class, 'store'])->name('store');

        Route::get('/{booking}', [TamuBookingController::class, 'show'])->name('show');
        Route::post('/{booking}/cancel', [TamuBookingController::class, 'cancel'])->name('cancel');
        Route::post('/{booking}/upload-payment', [TamuBookingController::class, 'uploadPayment'])
            ->name('upload-payment');
    });

    // ROOMS VIEW ONLY
    Route::get('/rooms', [RoomTamuController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomTamuController::class, 'show'])->name('rooms.show');

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN / RESEPSIONIS
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // REGISTER ADMIN / RESEPSIONIS
    Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('auth.register.store');

    // USERS MANAGEMENT
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('users.create');
    Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');

    // ROOMS
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    Route::post('/rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.update-status');

    // BOOKINGS ADMIN
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminBookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [AdminBookingController::class, 'show'])->name('show');
        Route::get('/{booking}/edit', [AdminBookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}', [AdminBookingController::class, 'update'])->name('update');
        Route::delete('/{booking}', [AdminBookingController::class, 'destroy'])->name('destroy');
    });

    // LOGOUT
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| RESEPSIONIS
|--------------------------------------------------------------------------
*/
Route::prefix('resepsionis')
    ->name('resepsionis.')
    ->middleware(['auth', 'resepsionis'])
    ->group(function () {

    Route::get('/dashboard', [ResepsionisController::class, 'dashboard'])->name('dashboard');

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [ResepsionisController::class, 'bookingsIndex'])->name('index');
        Route::get('/today', [ResepsionisController::class, 'todayBookings'])->name('today');
        Route::get('/{booking}', [ResepsionisController::class, 'bookingShow'])->name('show');
        Route::put('/{booking}/check-in', [ResepsionisController::class, 'checkIn'])->name('checkin');
        Route::put('/{booking}/check-out', [ResepsionisController::class, 'checkOut'])->name('checkout');
        Route::put('/{booking}/status', [ResepsionisController::class, 'updateStatus'])->name('status.update');
        Route::post('/{booking}/payment', [ResepsionisController::class, 'recordPayment'])->name('payment.record');
    });

    Route::get('/rooms', [ResepsionisController::class, 'roomsIndex'])->name('rooms.index');
    Route::get('/rooms/{room}', [ResepsionisController::class, 'roomShow'])->name('rooms.show');

    Route::prefix('guests')->name('guests.')->group(function () {
        Route::get('/', [ResepsionisController::class, 'guestsIndex'])->name('index');
        Route::get('/check-in', [ResepsionisController::class, 'checkInGuests'])->name('checkin');
        Route::get('/current', [ResepsionisController::class, 'currentGuests'])->name('current');
    });

    Route::post('/quick-checkin', [ResepsionisController::class, 'quickCheckIn'])->name('quick.checkin');
    Route::post('/quick-checkout', [ResepsionisController::class, 'quickCheckOut'])->name('quick.checkout');

    Route::get('/reports/daily', [ResepsionisController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/reports/occupancy', [ResepsionisController::class, 'occupancyReport'])->name('reports.occupancy');
});

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/rooms', [RoomTamuController::class, 'publicIndex'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomTamuController::class, 'publicShow'])->name('rooms.show');
});

// STATIC PAGES
Route::view('/contact', 'public.contact')->name('contact');
Route::view('/about', 'public.about')->name('about');
