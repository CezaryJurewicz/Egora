<?php

namespace App\Policies;

use App\SearchName;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class SearchNamePolicy
{
    use HandlesAuthorization, PolicyTrait;

    /**
     * Determine whether the user can view any search names.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the search name.
     *
     * @param  \App\User  $user
     * @param  \App\SearchName  $searchName
     * @return mixed
     */
    public function view(User $user, SearchName $searchName)
    {
//        return $this->allow();
    }

    /**
     * Determine whether the user can create search names.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can update the search name.
     *
     * @param  \App\User  $user
     * @param  \App\SearchName  $searchName
     * @return mixed
     */
    public function update(User $user, SearchName $searchName)
    {
        return $searchName->user->id == $user->id; 
    }

    /**
     * Determine whether the user can delete the search name.
     *
     * @param  \App\User  $user
     * @param  \App\SearchName  $searchName
     * @return mixed
     */
    public function delete(User $user, SearchName $searchName)
    {
        //
    }

    /**
     * Determine whether the user can restore the search name.
     *
     * @param  \App\User  $user
     * @param  \App\SearchName  $searchName
     * @return mixed
     */
    public function restore(User $user, SearchName $searchName)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the search name.
     *
     * @param  \App\User  $user
     * @param  \App\SearchName  $searchName
     * @return mixed
     */
    public function forceDelete(User $user, SearchName $searchName)
    {
        //
    }
}
