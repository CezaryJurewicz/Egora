<?php

namespace App\Policies;

use App\Nation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class NationPolicy
{
    use HandlesAuthorization, PolicyTrait;

    /**
     * Determine whether the user can view any nations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can view the nation.
     *
     * @param  \App\User  $user
     * @param  \App\Nation  $nation
     * @return mixed
     */
    public function view(User $user, Nation $nation)
    {
        //
    }

    /**
     * Determine whether the user can create nations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the nation.
     *
     * @param  \App\User  $user
     * @param  \App\Nation  $nation
     * @return mixed
     */
    public function update(User $user, Nation $nation)
    {
        //
    }

    /**
     * Determine whether the user can delete the nation.
     *
     * @param  \App\User  $user
     * @param  \App\Nation  $nation
     * @return mixed
     */
    public function delete(User $user, Nation $nation)
    {
        //
    }

    /**
     * Determine whether the user can restore the nation.
     *
     * @param  \App\User  $user
     * @param  \App\Nation  $nation
     * @return mixed
     */
    public function restore(User $user, Nation $nation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the nation.
     *
     * @param  \App\User  $user
     * @param  \App\Nation  $nation
     * @return mixed
     */
    public function forceDelete(User $user, Nation $nation)
    {
        //
    }
}
