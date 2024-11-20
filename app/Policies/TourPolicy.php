<?php

namespace App\Policies;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TourPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */

    public function update(User $user, Tour $tour): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tour $tour): bool
    {
        return $user->isAdmin();
    }
}
