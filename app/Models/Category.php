<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the lapangan associated with the category.
     */
    public function lapangan()
    {
        return $this->hasMany(Lapangan::class);
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
