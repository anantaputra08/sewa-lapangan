<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LapanganStatus extends Model
{
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'lapangan_id',
        'status',
        'date',
        'session_hour_ids',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'session_hour_ids' => 'array',
        'date' => 'date',
    ];
    /**
     * Get the lapangan that owns the LapanganStatus.
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}
