@extends('layouts.app')

@section('title', 'Edit Kamar - ' . $room->room_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <a href="{{ route('admin.rooms.index') }}" 
                   class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit Kamar</h1>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $room->room_number }}
                </span>
            </div>
            <p class="text-gray-600">Perbarui informasi kamar</p>
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

        <!-- Current Room Info -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-5 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-300">
                        <img src="{{ $room->foto ? Storage::url($room->foto) : asset('images/default-room.jpg') }}" 
                             alt="{{ $room->room_number }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Kamar {{ $room->room_number }}</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                         @if($room->status == 'available') bg-green-100 text-green-800
                                         @elseif($room->status == 'booked') bg-red-100 text-red-800
                                         @else bg-yellow-100 text-yellow-800 @endif">
                                <i class="fas fa-@if($room->status == 'available')check-circle @elseif($room->status == 'booked')bed @else tools @endif mr-1"></i>
                                {{ ucfirst($room->status) }}
                            </span>
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $room->roomType->name ?? 'N/A' }}
                            </span>
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    Terakhir diupdate: {{ $room->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Informasi Dasar -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informasi Dasar
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Room Number -->
                        <div>
                            <label for="room_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Kamar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-hashtag text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="room_number" 
                                       id="room_number"
                                       value="{{ old('room_number', $room->room_number) }}"
                                       class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>
                            </div>
                            @error('room_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room Type -->
                        <div>
                            <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Kamar <span class="text-red-500">*</span>
                            </label>
                            <select name="room_type_id" 
                                    id="room_type_id"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    required>
                                <option value="">-- Pilih Tipe Kamar --</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ old('room_type_id', $room->room_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga per Malam <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" 
                                       name="price" 
                                       id="price"
                                       value="{{ old('price', $room->price) }}"
                                       class="pl-12 w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       min="0"
                                       step="1000"
                                       required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                    {{ old('status', $room->status) == 'available' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" 
                                           name="status" 
                                           value="available"
                                           class="mr-2 text-green-600"
                                           {{ old('status', $room->status) == 'available' ? 'checked' : '' }}>
                                    <span>
                                        <div class="font-medium">Tersedia</div>
                                        <div class="text-xs text-gray-500">Siap ditempati</div>
                                    </span>
                                </label>
                                
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                    {{ old('status', $room->status) == 'booked' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                                    <input type="radio" 
                                           name="status" 
                                           value="booked"
                                           class="mr-2 text-red-600"
                                           {{ old('status', $room->status) == 'booked' ? 'checked' : '' }}>
                                    <span>
                                        <div class="font-medium">Terbooking</div>
                                        <div class="text-xs text-gray-500">Sudah dipesan</div>
                                    </span>
                                </label>
                                
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                    {{ old('status', $room->status) == 'maintenance' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                                    <input type="radio" 
                                           name="status" 
                                           value="maintenance"
                                           class="mr-2 text-yellow-600"
                                           {{ old('status', $room->status) == 'maintenance' ? 'checked' : '' }}>
                                    <span>
                                        <div class="font-medium">Maintenance</div>
                                        <div class="text-xs text-gray-500">Dalam perbaikan</div>
                                    </span>
                                </label>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Spesifikasi Kamar -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-ruler-combined text-blue-500 mr-2"></i>
                        Spesifikasi Kamar
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Kapasitas
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="number" 
                                       name="capacity" 
                                       id="capacity"
                                       value="{{ old('capacity', $room->capacity ?? 2) }}"
                                       class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       min="1"
                                       max="10">
                            </div>
                        </div>

                        <!-- Size -->
                        <div>
                            <label for="size" class="block text-sm font-medium text-gray-700 mb-2">
                                Ukuran Kamar
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-expand-arrows-alt text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="size" 
                                       id="size"
                                       value="{{ old('size', $room->size) }}"
                                       class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Contoh: 25m²">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fasilitas (Amenities) -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-concierge-bell text-blue-500 mr-2"></i>
                        Fasilitas Kamar
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $commonAmenities = [
                                'wifi' => ['icon' => 'wifi', 'label' => 'Wi-Fi'],
                                'ac' => ['icon' => 'snowflake', 'label' => 'AC'],
                                'tv' => ['icon' => 'tv', 'label' => 'TV'],
                                'minibar' => ['icon' => 'wine-bottle', 'label' => 'Minibar'],
                                'safe' => ['icon' => 'lock', 'label' => 'Safe Deposit'],
                                'hairdryer' => ['icon' => 'wind', 'label' => 'Hairdryer'],
                                'bathub' => ['icon' => 'bath', 'label' => 'Bathub'],
                                'shower' => ['icon' => 'shower', 'label' => 'Shower'],
                            ];
                            
                            $currentAmenities = json_decode($room->amenities ?? '[]', true) ?: [];
                        @endphp
                        
                        @foreach($commonAmenities as $key => $amenity)
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" 
                                   name="amenities[]" 
                                   value="{{ $key }}"
                                   class="mr-2 text-blue-600"
                                   {{ in_array($key, old('amenities', $currentAmenities)) ? 'checked' : '' }}>
                            <i class="fas fa-{{ $amenity['icon'] }} text-gray-400 mr-2"></i>
                            <span class="text-sm">{{ $amenity['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Foto Kamar -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-camera text-blue-500 mr-2"></i>
                        Foto Kamar
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Current Photo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Saat Ini
                            </label>
                            <div class="flex items-center gap-4">
                                @if($room->foto)
                                <div class="relative">
                                    <img src="{{ Storage::url($room->foto) }}" 
                                         alt="Current Photo" 
                                         class="w-48 h-48 object-cover rounded-lg border border-gray-300">
                                    <button type="button" 
                                            onclick="removePhoto()"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                        ×
                                    </button>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium">Foto saat ini</p>
                                    <p class="text-gray-500 mt-1">Klik tombol × untuk menghapus foto</p>
                                    <input type="hidden" name="remove_photo" id="remove-photo" value="0">
                                </div>
                                @else
                                <div class="text-center p-6 border-2 border-dashed border-gray-300 rounded-lg">
                                    <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500">Belum ada foto</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Upload New Photo -->
                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Foto Baru (Opsional)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-sm text-gray-600 mb-1">
                                        Klik untuk upload foto baru
                                    </p>
                                    <p class="text-xs text-gray-500 mb-3">
                                        Rekomendasi: JPG, PNG (Max 5MB)
                                    </p>
                                    <input type="file" 
                                           name="foto" 
                                           id="foto"
                                           class="hidden"
                                           accept="image/*">
                                    <label for="foto" 
                                           class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <!-- Preview -->
                            <div id="image-preview" class="mt-4 hidden">
                                <p class="text-sm text-gray-600 mb-2">Preview foto baru:</p>
                                <img id="preview-image" 
                                     class="w-48 h-48 object-cover rounded-lg border border-gray-300">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-sticky-note text-blue-500 mr-2"></i>
                        Catatan Tambahan
                    </h2>
                    
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan / Catatan
                        </label>
                        <textarea name="note" 
                                  id="note" 
                                  rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                  placeholder="Tambahkan catatan tentang kamar...">{{ old('note', $room->note) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Terakhir diubah: {{ $room->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.rooms.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-6 py-3 rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Update Kamar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function removePhoto() {
    document.getElementById('remove-photo').value = '1';
    alert('Foto akan dihapus setelah Anda klik "Update Kamar"');
}

document.addEventListener('DOMContentLoaded', function() {
    // Photo preview
    const photoInput = document.getElementById('foto');
    const preview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    });
});
</script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection