<?php

namespace App\Policies;

use App\Passport;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class PassportPolicy
{
    use HandlesAuthorization, PolicyTrait;

    /**
     * Determine whether the user can view any passports.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the passport.
     *
     * @param  \App\User  $user
     * @param  \App\Passport  $passport
     * @return mixed
     */
    public function view(User $user, Passport $passport)
    {
        //
    }

    /**
     * Determine whether the user can create passports.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the passport.
     *
     * @param  \App\User  $user
     * @param  \App\Passport  $passport
     * @return mixed
     */
    public function update(User $user, Passport $passport)
    {
        //
    }

    /**
     * Determine whether the user can delete the passport.
     *
     * @param  \App\User  $user
     * @param  \App\Passport  $passport
     * @return mixed
     */
    public function delete(User $user, Passport $passport)
    {
        return $passport->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the passport.
     *
     * @param  \App\User  $user
     * @param  \App\Passport  $passport
     * @return mixed
     */
    public function restore(User $user, Passport $passport)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the passport.
     *
     * @param  \App\User  $user
     * @param  \App\Passport  $passport
     * @return mixed
     */
    public function forceDelete(User $user, Passport $passport)
    {
        //
    }
}
