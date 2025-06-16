<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'lapangan_id',
        'date',
        'start_time',
        'end_time',
        'session_hours_ids',
        'total_price',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'session_hours_ids' => 'array',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Lapangan
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}
