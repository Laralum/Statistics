<?php

namespace Laralum\Statistics\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Laralum\Users\Models\User;

class ViewPolicy
{
    use HandlesAuthorization;

    /**
     * Filters the authoritzation.
     *
     * @param mixed $user
     * @param mixed $ability
     */
    public function before($user, $ability)
    {
        if (User::findOrFail($user->id)->superAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the current user can access statistics moule.
     *
     * @param mixed $user
     *
     * @return bool
     */
    public function access($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::statistics.access');
    }

    /**
     * Determine if the current user can access statistics moule.
     *
     * @param mixed $user
     *
     * @return bool
     */
    public function restart($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::statistics.restart');
    }
}
