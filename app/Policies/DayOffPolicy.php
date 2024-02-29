<?php

namespace App\Policies;

use App\Models\DayOff;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DayOffPolicy
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
    public function view(User $user, DayOff $model): bool
    {
        return ($user->isManager()
            && $user->company_id === $model->company_id)
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DayOff $model): bool
    {
        return $user->isAdmin()
            || ($user->isManager()
                && $user->company_id === $model->company_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DayOff $model): bool
    {
        return $user->isAdmin()
            || ($user->isManager()
                && $user->company_id === $model->company_id);
    }
}
