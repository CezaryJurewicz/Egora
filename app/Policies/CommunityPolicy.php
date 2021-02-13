<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;
use App\Community;

class CommunityPolicy
{
    use HandlesAuthorization, PolicyTrait;

    /**
     * Determine whether the user can view any community.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }
    
    /**
     * Determine whether the user can view the community.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function view(User $user, Community $community)
    {
        //
    }

}
