@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-3xl font-bold mb-6">
        @if(auth()->user()->role === 'admin')
            Dashboard Admin
        @else
            Dashboard Resepsionis
        @endif
    </h2>
    {{-- Enhanced Admin / Resepsionis Dashboard Cards --}}
    @if(auth()->user()->role === 'admin')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Users Card --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-md p-6 flex items-center justify-between hover:shadow-lg transform hover:-translate-y-1 transition">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 text-white mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.879 6.196 9 9 0 015.121 17.804z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Users</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $users ?? 0 }}</h3>
                    <p class="text-xs text-gray-400">Kelola semua pengguna</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg font-semibold hover:bg-indigo-100">Lihat</a>
        </div>

        {{-- Rooms Card --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-md p-6 flex items-center justify-between hover:shadow-lg transform hover:-translate-y-1 transition">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 text-white mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-10 4h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rooms</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $rooms ?? 0 }}</h3>
                    <p class="text-xs text-gray-400">Kelola kamar hotel</p>
                </div>
            </div>
            <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg font-semibold hover:bg-emerald-100">Lihat</a>
        </div>

        {{-- Bookings Card --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-md p-6 flex items-center justify-between hover:shadow-lg transform hover:-translate-y-1 transition">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-yellow-400 to-red-400 text-white mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h2.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H17a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Bookings</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $bookings ?? 0 }}</h3>
                    <p class="text-xs text-gray-400">Lihat semua pemesanan</p>
                </div>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-semibold hover:bg-yellow-100">Lihat</a>
        </div>

    </div>
    @endif

    @if(auth()->user()->role === 'resepsionis')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Rooms Card (resepsionis) --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-md p-6 flex items-center justify-between hover:shadow-lg transform hover:-translate-y-1 transition">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 text-white mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-10 4h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rooms</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $rooms ?? 0 }}</h3>
                    <p class="text-xs text-gray-400">Kelola kamar hotel</p>
                </div>
            </div>
            <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg font-semibold hover:bg-emerald-100">Lihat</a>
        </div>

        {{-- Bookings Card (resepsionis) --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-md p-6 flex items-center justify-between hover:shadow-lg transform hover:-translate-y-1 transition">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-yellow-400 to-orange-400 text-white mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h2.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H17a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Bookings</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $bookings ?? 0 }}</h3>
                    <p class="text-xs text-gray-400">Lihat semua pemesanan</p>
                </div>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-semibold hover:bg-yellow-100">Lihat</a>
        </div>

    </div>
    @endif
</div>
@endsection