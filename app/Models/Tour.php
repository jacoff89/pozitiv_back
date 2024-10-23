<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    /**
     * Получить поездки.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
