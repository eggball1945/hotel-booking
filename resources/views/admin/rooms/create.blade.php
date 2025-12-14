@extends('layouts.app')

@section('title', 'Tambah Kamar Baru')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <a href="{{ route('admin.rooms.index') }}" 
                   class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Kamar Baru</h1>
            </div>
            <p class="text-gray-600">Isi form berikut untuk menambahkan kamar baru</p>
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

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <div>
                    <p class="text-red-700 font-medium mb-1">Terdapat kesalahan:</p>
                    <ul class="text-red-600 text-sm list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow border border-gray-200">
            @csrf
            
            <div class="p-6 space-y-6">
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
                               value="{{ old('room_number') }}"
                               class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Contoh: 101, 201A"
                               required>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Nomor unik untuk identifikasi kamar</p>
                    @error('room_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Type -->
                <div>
                    <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Kamar <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="room_type_id" 
                                id="room_type_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none"
                                required>
                            <option value="">-- Pilih Tipe Kamar --</option>
                            @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" 
                                    data-price="{{ $type->price }}"
                                    {{ old('room_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} - Rp {{ number_format($type->price, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    
                    <!-- Selected room type info -->
                    <div id="room-type-info" class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-800" id="selected-room-name">-</p>
                                <p class="text-xs text-gray-600" id="selected-room-price">Rp 0</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Kapasitas: <span id="selected-room-capacity">-</span></p>
                                <p class="text-xs text-gray-500">Ukuran: <span id="selected-room-size">-</span></p>
                            </div>
                        </div>
                    </div>
                    
                    @error('room_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                            {{ old('status', 'available') == 'available' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                            <input type="radio" 
                                   name="status" 
                                   value="available"
                                   class="mr-2 text-green-600"
                                   {{ old('status', 'available') == 'available' ? 'checked' : '' }}>
                            <span>
                                <div class="font-medium">Tersedia</div>
                                <div class="text-xs text-gray-500">Siap ditempati</div>
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                            {{ old('status') == 'booked' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                            <input type="radio" 
                                   name="status" 
                                   value="booked"
                                   class="mr-2 text-red-600"
                                   {{ old('status') == 'booked' ? 'checked' : '' }}>
                            <span>
                                <div class="font-medium">Terbooking</div>
                                <div class="text-xs text-gray-500">Sudah dipesan</div>
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                            {{ old('status') == 'maintenance' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                            <input type="radio" 
                                   name="status" 
                                   value="maintenance"
                                   class="mr-2 text-yellow-600"
                                   {{ old('status') == 'maintenance' ? 'checked' : '' }}>
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

                <!-- Photo -->
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Kamar (Opsional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">
                                Klik untuk upload foto
                            </p>
                            <p class="text-xs text-gray-500 mb-3">
                                JPG, PNG (Max 5MB)
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
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img id="preview-image" 
                             class="w-48 h-48 object-cover rounded-lg border border-gray-300">
                    </div>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Note -->
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan (Opsional)
                    </label>
                    <textarea name="note" 
                              id="note" 
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Tambahkan catatan tentang kamar...">{{ old('note') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Contoh: "Lantai 3, view kota, ada balkon kecil"
                    </p>
                    @error('note')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Field dengan <span class="text-red-500">*</span> wajib diisi
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.rooms.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-6 py-2 rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Simpan Kamar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
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
    
    // Room type info display
    const roomTypeSelect = document.getElementById('room_type_id');
    const roomTypeInfo = document.getElementById('room-type-info');
    const roomNameSpan = document.getElementById('selected-room-name');
    const roomPriceSpan = document.getElementById('selected-room-price');
    const roomCapacitySpan = document.getElementById('selected-room-capacity');
    const roomSizeSpan = document.getElementById('selected-room-size');
    
    // Define default capacities and sizes based on room type name
    const getRoomTypeDetails = (roomTypeName) => {
        const name = roomTypeName.toLowerCase();
        
        if (name.includes('single') || name.includes('tunggal')) {
            return { capacity: '1 orang', size: '18m²' };
        } else if (name.includes('double') || name.includes('ganda')) {
            return { capacity: '2 orang', size: '25m²' };
        } else if (name.includes('twin')) {
            return { capacity: '2 orang', size: '25m²' };
        } else if (name.includes('deluxe')) {
            return { capacity: '2 orang', size: '30m²' };
        } else if (name.includes('family') || name.includes('keluarga')) {
            return { capacity: '4 orang', size: '35m²' };
        } else if (name.includes('suite')) {
            return { capacity: '2 orang', size: '40m²' };
        } else {
            return { capacity: '2 orang', size: '25m²' };
        }
    };
    
    roomTypeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const roomName = selectedOption.text.split(' - ')[0];
            const roomPrice = selectedOption.getAttribute('data-price');
            const details = getRoomTypeDetails(roomName);
            
            roomNameSpan.textContent = roomName;
            roomPriceSpan.textContent = 'Rp ' + parseInt(roomPrice).toLocaleString('id-ID');
            roomCapacitySpan.textContent = details.capacity;
            roomSizeSpan.textContent = details.size;
            
            roomTypeInfo.classList.remove('hidden');
        } else {
            roomTypeInfo.classList.add('hidden');
        }
    });
    
    // Trigger change on page load if there's a selected value
    if (roomTypeSelect.value) {
        roomTypeSelect.dispatchEvent(new Event('change'));
    }
    
});
</script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection