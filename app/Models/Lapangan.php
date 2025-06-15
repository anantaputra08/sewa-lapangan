<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
