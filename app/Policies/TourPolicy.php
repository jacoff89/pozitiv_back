<?php

namespace App\Policies;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TourPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tour $tour): bool
    {
        return $user->isAdmin();
    }
}
