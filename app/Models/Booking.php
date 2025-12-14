<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',  // Tambahkan ini
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'total_nights',
        'total_price',
        'status',
        'special_notes',
        'payment_proof',  // Tambahkan ini
            'actual_check_in',
    'actual_check_out',
    'additional_charges',
    'total_paid',
    'payment_status'
];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'additional_charges' => 'decimal:2',
        'total_paid' => 'decimal:2'
        // 'total_price' => 'decimal:2', // Hapus atau comment ini karena di database int(11)
    ];

    protected $appends = [
        'status_text',
        'status_color',
        'formatted_total_price', // Tambahkan accessor untuk format harga
    ];

    /**
     * Boot method untuk auto-generate booking code
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            // Generate booking code jika belum ada
            if (empty($booking->booking_code)) {
                $booking->booking_code = self::generateBookingCode();
            }
        });
    }

    /**
     * Method untuk generate booking code yang unik
     */
    public static function generateBookingCode()
    {
        $code = 'BOOK' . date('Ymd') . strtoupper(Str::random(6));
        
        // Cek apakah code sudah ada
        while (self::where('booking_code', $code)->exists()) {
            $code = 'BOOK' . date('Ymd') . strtoupper(Str::random(6));
        }
        
        return $code;
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi ke room type melalui room
    public function roomType()
    {
        return $this->hasOneThrough(
            RoomType::class, 
            Room::class, 
            'id',           // Foreign key pada tabel rooms
            'id',           // Foreign key pada tabel room_types
            'room_id',      // Local key pada tabel bookings
            'room_type_id'  // Local key pada tabel rooms
        );
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'checked_in' => 'Check-in',
            'checked_out' => 'Check-out',
            'cancelled' => 'Dibatalkan'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'success',
            'checked_in' => 'primary',
            'checked_out' => 'info',
            'cancelled' => 'danger'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    // Accessor untuk format harga
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Accessor untuk format check-in date
    public function getFormattedCheckInAttribute()
    {
        return $this->check_in ? $this->check_in->format('d-m-Y') : null;
    }

    // Accessor untuk format check-out date
    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out ? $this->check_out->format('d-m-Y') : null;
    }

    // Method untuk menghitung total malam
    public static function calculateNights($check_in, $check_out)
    {
        $start = new \DateTime($check_in);
        $end = new \DateTime($check_out);
        return $start->diff($end)->days;
    }

    // Scope untuk booking aktif
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'checked_in']);
    }

    // Scope untuk booking user tertentu
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk booking berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Method untuk cek apakah bisa dibatalkan
    public function canBeCancelled()
    {
        return $this->status === 'pending' && 
               $this->check_in > now()->addDays(1); // Minimal 1 hari sebelum check-in
    }

    // Method untuk cek apakah sudah expired (pending lebih dari 24 jam)
    public function isExpired()
    {
        if ($this->status !== 'pending') {
            return false;
        }
        
        $createdAt = new \DateTime($this->created_at);
        $now = new \DateTime();
        $interval = $createdAt->diff($now);
        
        return $interval->h >= 24 || $interval->days > 0;
    }
    public function payments()
{
    return $this->hasMany(Payment::class);
}

}