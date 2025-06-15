<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionHour extends Model
{
    protected $fillable = [
        'description',
        'day_id',
        'start_time',
        'end_time',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}
