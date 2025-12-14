<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type_id',
        'status',
        'note',
        'foto',
    ];

    // Relasi ke room_type
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // Accessor untuk foto URL
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }
        
        return asset('images/default-room.jpg');
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        $statuses = [
            'available' => 'Tersedia',
            'booked' => 'Terbooking',
            'maintenance' => 'Maintenance'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        $colors = [
            'available' => 'green',
            'booked' => 'red',
            'maintenance' => 'yellow'
        ];
        
        return $colors[$this->status] ?? 'gray';
    }
}