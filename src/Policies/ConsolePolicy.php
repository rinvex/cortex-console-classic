<?php

declare(strict_types=1);

namespace Cortex\Attributable\Policies;

use Cortex\Fort\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can run web terminal.
     *
     * @param string                   $ability
     * @param \Cortex\Fort\Models\User $user
     *
     * @return bool
     */
    public function runTerminal($ability, User $user)
    {
        return $user->allAbilities->pluck('slug')->contains($ability);
    }

    /**
     * Determine whether the user can list routes.
     *
     * @param string                   $ability
     * @param \Cortex\Fort\Models\User $user
     *
     * @return bool
     */
    public function listRoutes($ability, User $user)
    {
        return $user->allAbilities->pluck('slug')->contains($ability);
    }
}
