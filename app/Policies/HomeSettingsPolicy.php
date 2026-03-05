<?php

namespace App\Policies;

use App\Models\HomeSettings;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HomeSettingsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRoles(['admin', 'editor', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HomeSettings $homeSettings): bool
    {
        return $user->hasRoles(['admin', 'editor', 'user']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRoles(['admin', 'editor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HomeSettings $homeSettings): bool
    {
        return $user->hasRoles(['admin', 'editor']);
    }

    /**
     * Determine whether the user can bulk delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasRoles(['admin', 'editor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HomeSettings $homeSettings): bool
    {
        return $user->hasRoles(['admin', 'editor']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HomeSettings $homeSettings): bool
    {
        return $user->hasRoles(['admin', 'editor']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HomeSettings $homeSettings): bool
    {
        return $user->hasRoles(['admin', 'editor']);
    }
}
