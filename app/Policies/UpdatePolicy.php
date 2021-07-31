<?php

namespace App\Policies;

use App\Update;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UpdatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any updates.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can view the update.
     *
     * @param  \App\User  $user
     * @param  \App\Update  $update
     * @return mixed
     */
    public function view(User $user, Update $update)
    {
        //
    }

    /**
     * Determine whether the user can create updates.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the update.
     *
     * @param  \App\User  $user
     * @param  \App\Update  $update
     * @return mixed
     */
    public function update(User $user, Update $update)
    {
        //
    }

    /**
     * Determine whether the user can delete the update.
     *
     * @param  \App\User  $user
     * @param  \App\Update  $update
     * @return mixed
     */
    public function delete(User $user, Update $update)
    {
        return ($user->id == $update->user->id);
    }

    /**
     * Determine whether the user can restore the update.
     *
     * @param  \App\User  $user
     * @param  \App\Update  $update
     * @return mixed
     */
    public function restore(User $user, Update $update)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the update.
     *
     * @param  \App\User  $user
     * @param  \App\Update  $update
     * @return mixed
     */
    public function forceDelete(User $user, Update $update)
    {
        //
    }
}
