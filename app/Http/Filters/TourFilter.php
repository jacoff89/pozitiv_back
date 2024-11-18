<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class TourFilter
{
    public function apply(Builder $query, array $filters)
    {
        if (isset($filters['name'])) {
            $query->where('name', $filters['name']);
        }
        return $query;
    }
}
