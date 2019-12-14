<?php

namespace App\Policies;

use App\User;
use App\UserType;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class UserTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any user types.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->deny();
    }

    /**
     * Determine whether the user can view the user type.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function view(User $user, UserType $userType)
    {
        //
    }

    /**
     * Determine whether the user can create user types.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the user type.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function update(User $user, UserType $userType)
    {
        //
    }

    /**
     * Determine whether the user can delete the user type.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function delete(User $user, UserType $userType)
    {
        //
    }

    /**
     * Determine whether the user can restore the user type.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function restore(User $user, UserType $userType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the user type.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function forceDelete(User $user, UserType $userType)
    {
        //
    }
}
