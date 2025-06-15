<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lapangan extends Model
{
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'price',
        'photo',
        'status',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];
    protected $appends = ['photo_url'];
    /**
     * Get the category that owns the Lapangan.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mendefinisikan relasi "hasMany" ke model Booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Menggunakan Storage::url() untuk mendapatkan URL publik
            // yang akan digabungkan dengan APP_URL secara otomatis.
            return Storage::disk('public')->url($this->photo);
        }
        return null;
    }
}
