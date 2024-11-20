<?php

namespace App\Policies;

use App\Models\AdditionalService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdditionalServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdditionalService $additionalService): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdditionalService $additionalService): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdditionalService $additionalService): bool
    {
        return $user->isAdmin();
    }
}
