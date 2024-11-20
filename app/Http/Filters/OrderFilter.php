<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter
{
    public function apply(Builder $query, array $filters)
    {
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        if (isset($filters['trip_id'])) {
            $query->where('trip_id', $filters['trip_id']);
        }
        return $query;
    }
}
