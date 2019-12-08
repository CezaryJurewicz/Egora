<?php

namespace App\Policies;

use App\Idea;
use App\User;
use App\Nation;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class IdeaPolicy
{
    use HandlesAuthorization, PolicyTrait;

    /**
     * Determine whether the user can view any ideas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->allow();
    }
    
    public function administrate(User $user)
    {
        return $this->deny();
    }

    /**
     * Determine whether the user can view the idea.
     *
     * @param  \App\User  $user
     * @param  \App\Idea  $idea
     * @return mixed
     */
    public function view(User $user, Idea $idea)
    {
//        if ($user->user_type->class == 'user' && $idea->nation->title=='Egora') {
//            return $this->deny();
//        }

        return $this->allow();
    }
    
    public function like(User $user, Idea $idea)
    {
        if ($user->user_type->class == 'user' && $idea->nation->title=='Egora') {
            return $this->deny();
        }
        
        $nations = Nation::whereIn('title', ['Egora', 'Universal', $user->nation->title])->get()->pluck('id');
        
        if (!$nations->contains($idea->nation->id)) {
            return $this->deny();
        }
        
        return $this->allow();
    }
    
    public function unlike(User $user, Idea $idea)
    {
        return $this->allow();
    }
    
    
    /**
     * Determine whether the user can create ideas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can update the idea.
     *
     * @param  \App\User  $user
     * @param  \App\Idea  $idea
     * @return mixed
     */
    public function update(User $user, Idea $idea)
    {
        //
    }

    /**
     * Determine whether the user can delete the idea.
     *
     * @param  \App\User  $user
     * @param  \App\Idea  $idea
     * @return mixed
     */
    public function delete(User $user, Idea $idea)
    {
        //
    }

    /**
     * Determine whether the user can restore the idea.
     *
     * @param  \App\User  $user
     * @param  \App\Idea  $idea
     * @return mixed
     */
    public function restore(User $user, Idea $idea)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the idea.
     *
     * @param  \App\User  $user
     * @param  \App\Idea  $idea
     * @return mixed
     */
    public function forceDelete(User $user, Idea $idea)
    {
        //
    }
}
