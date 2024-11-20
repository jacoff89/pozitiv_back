<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    /**
     * Получить доп. услуги.
     */
    public function additionalServices(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalService::class)->withPivot('count');
    }

    /**
     * Получить туристов.
     */
    public function tourists(): BelongsToMany
    {
        return $this->belongsToMany(Tourist::class);
    }

    /**
     * Получить юзера, создавшего заказ.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить поездку, на которую сделан заказ.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class)->with('additionalServices');
    }
}
