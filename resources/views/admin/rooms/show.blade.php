@extends('layouts.app')

@section('title', 'Detail Kamar - ' . $room->room_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.rooms.index') }}" 
                       class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800">Detail Kamar</h1>
                    <span class="text-2xl font-bold text-gray-600">{{ $room->room_number }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.rooms.edit', $room) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                </div>
            </div>
            
            <!-- Room Status Bar -->
            <div class="bg-white rounded-xl shadow border border-gray-200 p-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <!-- Status Badge -->
                        <div class="flex items-center gap-2">
                            @php
                                $statusConfig = [
                                    'available' => ['color' => 'green', 'icon' => 'check-circle', 'text' => 'Tersedia'],
                                    'booked' => ['color' => 'red', 'icon' => 'bed', 'text' => 'Terbooking'],
                                    'maintenance' => ['color' => 'yellow', 'icon' => 'tools', 'text' => 'Maintenance']
                                ];
                                $config = $statusConfig[$room->status] ?? ['color' => 'gray', 'icon' => 'question-circle', 'text' => $room->status];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                         bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                <i class="fas fa-{{ $config['icon'] }} mr-2"></i>
                                {{ $config['text'] }}
                            </span>
                        </div>
                        
                        <!-- Room Type -->
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tag text-gray-400"></i>
                            <span class="font-medium">{{ $room->roomType->name ?? 'N/A' }}</span>
                        </div>
                        
                        <!-- Created Date -->
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                            <span class="text-gray-600">Ditambah: {{ $room->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-id-badge mr-1"></i>
                        ID: {{ $room->id }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Photo & Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Photo Section -->
                <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-camera text-blue-500 mr-2"></i>
                            Foto Kamar
                        </h2>
                    </div>
                    <div class="p-5">
                        @if($room->foto)
                        <img src="{{ Storage::url($room->foto) }}" 
                             alt="Kamar {{ $room->room_number }}" 
                             class="w-full h-96 object-cover rounded-lg shadow">
                        @else
                        <div class="text-center py-12">
                            <i class="fas fa-image text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Tidak ada foto tersedia</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Specifications -->
                <div class="bg-white rounded-xl shadow border border-gray-200">
                    <div class="p-5 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Informasi Kamar
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Kamar</label>
                                    <p class="font-semibold text-gray-800 text-xl">{{ $room->room_number }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Tipe Kamar</label>
                                    <p class="font-semibold text-gray-800">{{ $room->roomType->name ?? 'N/A' }}</p>
                                    @if($room->roomType)
                                        <p class="text-sm text-gray-600 mt-1">Rp {{ number_format($room->roomType->price, 0, ',', '.') }} per malam</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
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
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Catatan</label>
                                    @if($room->note)
                                        <p class="text-gray-700 whitespace-pre-line bg-gray-50 p-3 rounded-lg">{{ $room->note }}</p>
                                    @else
                                        <p class="text-gray-400">Tidak ada catatan</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
                                    <p class="text-gray-600">{{ $room->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Dibuat Pada</label>
                                    <p class="text-gray-600">{{ $room->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Actions -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow border border-gray-200">
                    <div class="p-5 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-bolt text-blue-500 mr-2"></i>
                            Aksi Cepat
                        </h2>
                    </div>
                    <div class="p-5 space-y-3">
                        <!-- Status Change -->
                        <form action="{{ route('admin.rooms.update-status', $room) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('POST')
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Status:</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="submit" name="status" value="available" 
                                        class="bg-green-100 hover:bg-green-200 text-green-800 font-medium px-3 py-2 rounded-lg text-sm transition flex items-center justify-center gap-1">
                                    <i class="fas fa-check-circle"></i>
                                    Tersedia
                                </button>
                                <button type="submit" name="status" value="booked" 
                                        class="bg-red-100 hover:bg-red-200 text-red-800 font-medium px-3 py-2 rounded-lg text-sm transition flex items-center justify-center gap-1">
                                    <i class="fas fa-bed"></i>
                                    Booking
                                </button>
                                <button type="submit" name="status" value="maintenance" 
                                        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-medium px-3 py-2 rounded-lg text-sm transition flex items-center justify-center gap-1">
                                    <i class="fas fa-tools"></i>
                                    Perbaikan
                                </button>
                            </div>
                        </form>
                        
                        <!-- Delete -->
                        <form action="{{ route('admin.rooms.destroy', $room) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg transition flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i>
                                Hapus Kamar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Room Type Info -->
                @if($room->roomType)
                <div class="bg-white rounded-xl shadow border border-gray-200">
                    <div class="p-5 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Informasi Tipe Kamar
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Tipe</p>
                                <p class="font-semibold text-gray-800">{{ $room->roomType->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Harga Dasar</p>
                                <p class="font-semibold text-green-600">Rp {{ number_format($room->roomType->price, 0, ',', '.') }}</p>
                            </div>
                            @if($room->roomType->description)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Deskripsi</p>
                                <p class="text-sm text-gray-600">{{ $room->roomType->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Back to List -->
                <div class="bg-white rounded-xl shadow border border-gray-200">
                    <div class="p-5">
                        <a href="{{ route('admin.rooms.index') }}" 
                           class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-3 rounded-lg transition flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Daftar Kamar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="mt-6 bg-white rounded-xl shadow border border-gray-200 p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-id-badge"></i>
                    <span>ID: {{ $room->id }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Dibuat: {{ $room->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-edit"></i>
                    <span>Diupdate: {{ $room->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection