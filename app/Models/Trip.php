<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Получить доп. услуги.
     */
    public function additionalServices(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalService::class)->withPivot('cost', 'bonuses');
    }

    /**
     * Получить заказы.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
