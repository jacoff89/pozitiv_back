<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tourist extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    /**
     * Получить юзера, который создал туриста.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
