<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter
{
    public function apply(Builder $query, array $filters)
    {
        if (isset($filters['email'])) {
            $query->where('email', $filters['email']);
        }
        return $query;
    }
}
