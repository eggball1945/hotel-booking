@extends('layouts.app')

@section('title', 'Manajemen Kamar')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Kamar</h1>
                <p class="text-gray-600 mt-1">Kelola data kamar hotel Anda</p>
            </div>
            <a href="{{ route('admin.rooms.create') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg transition shadow">
                <i class="fas fa-plus"></i>
                Tambah Kamar Baru
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Kamar</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-door-open text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Tersedia</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['available'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Terbooking</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['booked'] ?? 0 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-bed text-red-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Maintenance</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['maintenance'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-tools text-yellow-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Search Section -->
    <div class="bg-white rounded-xl shadow border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('admin.rooms.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nomor/tipe/catatan..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Terbooking</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                
                <!-- Room Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kamar</label>
                    <select name="room_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tipe</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" 
                                {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Sorting -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort_by" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="room_number" {{ request('sort_by') == 'room_number' ? 'selected' : '' }}>Nomor Kamar</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Ditambah</option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $rooms->firstItem() ?? 0 }} - {{ $rooms->lastItem() ?? 0 }} dari {{ $rooms->total() }} kamar
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.rooms.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-redo"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Success/Error Messages -->
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

    <!-- Room Table -->
    <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kamar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 rounded-lg overflow-hidden border border-gray-200">
                                    @if($room->foto)
                                        <img src="{{ Storage::url($room->foto) }}" 
                                             alt="Room {{ $room->room_number }}" 
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-bed text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="font-semibold text-gray-900">Kamar {{ $room->room_number }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        ID: {{ $room->id }} • Dibuat: {{ $room->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $room->roomType->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'available' => ['color' => 'green', 'icon' => 'check-circle', 'text' => 'Tersedia'],
                                    'booked' => ['color' => 'red', 'icon' => 'bed', 'text' => 'Terbooking'],
                                    'maintenance' => ['color' => 'yellow', 'icon' => 'tools', 'text' => 'Maintenance']
                                ];
                                $config = $statusConfig[$room->status] ?? ['color' => 'gray', 'icon' => 'question-circle', 'text' => $room->status];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                         bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                <i class="fas fa-{{ $config['icon'] }} mr-1.5"></i>
                                {{ $config['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($room->note)
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $room->note }}</div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <!-- View Button -->
                                <a href="{{ route('admin.rooms.show', $room) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('admin.rooms.edit', $room) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50 transition"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Status Quick Actions -->
                                <div class="relative group">
                                    <button class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition"
                                            title="Ubah Status">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden group-hover:block">
                                        <form action="{{ route('admin.rooms.update-status', $room) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" name="status" value="available" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                                Set Tersedia
                                            </button>
                                            <button type="submit" name="status" value="booked" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700">
                                                <i class="fas fa-bed mr-2 text-red-500"></i>
                                                Set Terbooking
                                            </button>
                                            <button type="submit" name="status" value="maintenance" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700">
                                                <i class="fas fa-tools mr-2 text-yellow-500"></i>
                                                Set Maintenance
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('admin.rooms.destroy', $room) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-400 mb-3">
                                <i class="fas fa-door-closed text-4xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada data kamar</p>
                            <p class="text-gray-400 mt-1">Tambahkan kamar baru untuk memulai</p>
                            <a href="{{ route('admin.rooms.create') }}" 
                               class="inline-flex items-center gap-2 mt-4 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-plus"></i>
                                Tambah Kamar Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($rooms->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $rooms->firstItem() }} - {{ $rooms->lastItem() }} dari {{ $rooms->total() }} hasil
                </div>
                <div>
                    {{ $rooms->withQueryString()->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection