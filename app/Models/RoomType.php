<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomType extends Model
{
    use HasFactory;

    // Nama tabel (jika tidak default)
    protected $table = 'room_types';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'name',
        'description',
        'price',
        'photo',
    ];

    // Casting tipe data
    protected $casts = [
        'price' => 'integer',
    ];

    // Relasi ke rooms
    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Accessor untuk photo URL
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Cek apakah foto sudah berupa URL lengkap atau path storage
            if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
                return $this->photo;
            }
            
            // Coba dari storage
            if (Storage::disk('public')->exists($this->photo)) {
                return Storage::url($this->photo);
            }
            
            // Coba dari folder public
            if (file_exists(public_path('storage/' . $this->photo))) {
                return asset('storage/' . $this->photo);
            }
        }
        
        // Default image jika tidak ada foto
        return asset('images/default-room-type.jpg');
    }

    // Scope untuk active room types (berdasarkan logika sederhana)
    public function scopeActive($query)
    {
        return $query->where('price', '>', 0); // Contoh: anggap aktif jika harga > 0
    }

    // Scope untuk ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('price')->orderBy('name');
    }

    // Method untuk mendapatkan nama dengan harga
    public function getNameWithPriceAttribute()
    {
        return $this->name . ' (Rp ' . number_format($this->price, 0, ',', '.') . ')';
    }

    // Method untuk kapasitas default (jika tidak ada di tabel)
    public function getDefaultCapacityAttribute()
    {
        // Berdasarkan nama kamar, tentukan kapasitas default
        $name = strtolower($this->name);
        
        if (str_contains($name, 'single') || str_contains($name, 'tunggal')) {
            return 1;
        } elseif (str_contains($name, 'double') || str_contains($name, 'ganda')) {
            return 2;
        } elseif (str_contains($name, 'twin')) {
            return 2;
        } elseif (str_contains($name, 'family') || str_contains($name, 'keluarga')) {
            return 4;
        } elseif (str_contains($name, 'suite')) {
            return 2;
        } else {
            return 2; // Default
        }
    }

    // Method untuk ukuran default (jika tidak ada di tabel)
    public function getDefaultSizeAttribute()
    {
        // Berdasarkan nama kamar, tentukan ukuran default
        $name = strtolower($this->name);
        
        if (str_contains($name, 'single') || str_contains($name, 'tunggal')) {
            return '18m²';
        } elseif (str_contains($name, 'double') || str_contains($name, 'ganda')) {
            return '25m²';
        } elseif (str_contains($name, 'deluxe')) {
            return '30m²';
        } elseif (str_contains($name, 'suite')) {
            return '40m²';
        } elseif (str_contains($name, 'family') || str_contains($name, 'keluarga')) {
            return '35m²';
        } else {
            return '25m²'; // Default
        }
    }

    // Method untuk kategori (jika tidak ada di tabel)
    public function getCategoryAttribute()
    {
        $name = strtolower($this->name);
        
        if (str_contains($name, 'single') || str_contains($name, 'tunggal')) {
            return 'standard';
        } elseif (str_contains($name, 'double') || str_contains($name, 'ganda') || str_contains($name, 'twin')) {
            return 'standard';
        } elseif (str_contains($name, 'deluxe')) {
            return 'deluxe';
        } elseif (str_contains($name, 'suite')) {
            return 'suite';
        } elseif (str_contains($name, 'family') || str_contains($name, 'keluarga')) {
            return 'family';
        } elseif (str_contains($name, 'vip') || str_contains($name, 'executive') || str_contains($name, 'president')) {
            return 'vip';
        } else {
            return 'standard';
        }
    }
}