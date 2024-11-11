<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    protected $casts = [
        'dateStart' => 'date:d.m.Y',
        'dateEnd' => 'date:d.m.Y',
    ];

    /**
     * Получить тур, которому принадлежит поездка.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
