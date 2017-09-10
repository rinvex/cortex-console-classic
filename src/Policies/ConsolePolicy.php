<?php

declare(strict_types=1);

namespace Cortex\Console\Policies;

use Rinvex\Fort\Contracts\UserContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can run web terminal.
     *
     * @param string                              $ability
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return bool
     */
    public function runTerminal($ability, UserContract $user)
    {
        return $user->allAbilities->pluck('slug')->contains($ability);
    }

    /**
     * Determine whether the user can list routes.
     *
     * @param string                              $ability
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return bool
     */
    public function listRoutes($ability, UserContract $user)
    {
        return $user->allAbilities->pluck('slug')->contains($ability);
    }
}
