<?php

namespace App\Policies;

use App\User;
use App\SearchName;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if (!in_array($ability, ['reset', 'update', 'disqualify_membership', 'verify'])) {        
            if ($user->isAdmin()) {
                return $this->allow();
            }
        }
    }
    
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isGuardian();
    }
    
    public function search(User $user)
    {
        return $this->allow();
    }
    
    public function searchAny(User $user)
    {
        return $this->allow();
    }
    
    public function leads(User $user)
    {
        return is_egora('community');
    }
    
    public function leadsbyid(User $user, $hash)
    {
        $searchname = SearchName::with('user')->where('hash', $hash)->first();

        if ($searchname) {
            $model = $searchname->user;

            return $user->id !== $model->id;
        }
        
        return $this->deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->id == $model->id;
    }
    
    public function settings(User $user, User $model)
    {
        return $user->id == $model->id;
    }
    
    public function deactivate(User $user, User $model)
    {
        if ($user->isAdmin() || $user->isGuardian()) {
            return $this->allow();
        }
        
        return $this->deny();
    }
    
    public function follow(User $user, User $model)
    {
        return (!$user->followingUser($model) && ($user->following->count()<300) && ($user->id != $model->id));
    }
    
    public function unfollow(User $user, User $model)
    {
        return $user->followingUser($model);
    }
    
    public function submit_application(User $user)
    {
        return !$user->user_type->isIlp && $user->user_type->former == 0;
    }
    
    public function accept_application(User $user, User $model)
    {
        //
    }
    
    public function ideological_profile(User $user, $hash)
    {
        $searchname = SearchName::with('user')->where('hash', $hash)->first();
        
        if ($searchname) {
            $model = $searchname->user;

            return (!$model->trashed())? $this->allow() : $this->deny() ;
        }
        
        return $this->deny();
    }
    
    public function about_edit(User $user, $hash)
    {
        $searchname = SearchName::with('user')->where('hash', $hash)->first();
        
        if ($searchname) {
            $model = $searchname->user;

            return $model->id == $user->id;
        }
        
        return $this->deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $user->id == $model->id && $user->user_type->class == 'user';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        if ($user->isAdmin() || $user->isGuardian()) {
            return $this->allow();
        }
        
        return $this->deny();
    }
    
    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
    
    public function verify(User $user, User $model)
    {
        if ($user->isAdmin() || $user->isGuardian()) {
            return $this->allow();
        }

        return $this->deny();
    }
    
    public function unverify(User $user, User $model)
    {
        if ($user->isAdmin() || $user->isGuardian()) {
            return $this->allow();
        }
        
        return $this->deny();
    }

    public function submit_officer_application(User $user, User $model)
    {
        return $user->id == $model->id && $model->user_type->isIlp && $model->user_type->isVerified && is_null($model->petition);
    }
    
    public function cancel_officer_application(User $user, User $model)
    {
        return $user->id == $model->id && $model->user_type->isIlp && $model->user_type->isVerified && $model->petition;
    }
    
    public function support_officer_application(User $user, User $model)
    {        
        //NOTE: allow own support
        return ($model->nation->id == $user->nation->id) && $user->user_type->isIlp && $user->user_type->verified
                && $model->petition && $model->petition->supporters->count() < 46 
                && !$model->petition->supporters->pluck('id')->contains($user->id);
    }
    
    public function unsupport_officer_application(User $user, User $model)
    {
        return $user->user_type->isIlp && $model->petition && $model->petition->supporters->pluck('id')->contains($user->id);
    }

    public function disqualify_membership(User $user, User $model) 
    {
        if ($user->isAdmin() && $user->guardianship && $model->user_type->class !== 'user' && !$model->user_type->former) {
            return $this->allow();
        }

        return $this->deny();
    }
        
    public function withdraw_from_ilp(User $user, User $model) 
    {
        return $user->id == $model->id && $model->user_type->isIlp;
    }    
    
    public function home(User $user) 
    {
        return is_egora();
    }    
    
    public function municipal(User $user) 
    {
        return is_egora('municipal');
    }    
    
    public function community(User $user) 
    {
        return is_egora('community');
    }    
    
    public function invite(User $user, User $model, \App\Idea $idea) 
    {
//        if (is_egora('community') && !$model->communities->contains($idea->community)) {
//            return $this->deny();
//        }
        
        if ($user->notifications_disabled_by->contains($model)) {
            return $this->deny();
        }
        
        return $this->allow();
    }    
    
    public function switch(User $user, $key) 
    {
        return $this->allow();
    }    

    public function reset(User $user, User $model)
    {
        return $user->isAdmin() && $model->user_type->former;
    }
    
    public function communities(User $user, $hash)
    {
        $searchname = SearchName::with('user')->where('hash', $hash)->first();
        
        if ($searchname) {
            $model = $searchname->user;
            
            return is_egora('community') && $user->id == $model->id;
        }
        
        return $this->deny();
    }

    public function municipality_update(User $user, $hash)
    {
        $searchname = SearchName::with('user')->where('hash', $hash)->first();
        
        if ($searchname) {
            $model = $searchname->user;
            
            return is_egora('municipal') && $user->id == $model->id;
        }
        
        return $this->deny();
    }
    
    public function disqualify(User $user, User $model) 
    {
        return (!$user->disqualifyingUser($model));
    }
    
    public function qualify(User $user, User $model) 
    {
        return ($user->disqualifyingUser($model));
    }    
}
