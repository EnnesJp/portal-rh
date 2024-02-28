<?php

namespace App\Policies;

use App\Models\Punch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PunchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Punch $punch): bool
    {
        return $user->id === $punch->user_id
            || ($user->isManager()
                && $user->company_id === $punch->user->company_id)
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Punch $punch): bool
    {
        return $user->isAdmin()
            || ($user->isManager()
                && $user->company_id === $punch->user->company_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Punch $punch): bool
    {
        return $user->isAdmin()
            || ($user->isManager()
                && $user->company_id === $punch->user->company_id);
    }
}
