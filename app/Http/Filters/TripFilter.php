<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class TripFilter
{
    public function apply(Builder $query, array $filters)
    {
        if (isset($filters['tour_id'])) {
            $query->where('tour_id', $filters['tour_id']);
        }
        return $query;
    }
}
