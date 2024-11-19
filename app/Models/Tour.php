<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'place',
        'plan',
        'plan_picture',
        'season',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Получить поездки.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
