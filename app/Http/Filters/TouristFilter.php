<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class TouristFilter
{
    public function apply(Builder $query, array $filters)
    {
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        return $query;
    }
}
