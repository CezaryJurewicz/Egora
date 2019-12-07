<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if (!in_array($ability, ['disqualify_membership', 'cancel_guardianship', 'allow_guardianship', 'verify'])) {        
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
        //
    }
    
    public function searchAny(User $user)
    {
        return $this->allow();
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
        return $user->id == $model->id;
    }
    
    public function follow(User $user, User $model)
    {
        return $user->id != $model->id;
    }
    
    public function submit_application(User $user, User $model)
    {
        return $user->id == $model->id;
    }
    
    public function accept_application(User $user, User $model)
    {
        //
    }
    
    public function ideological_profile(User $user, User $model)
    {
        return $this->allow();
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
        //
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
        if ($user->isAdmin() && $user->guardianship) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function ilp_signup(User $user, User $model)
    {
        return $user->id == $model->id && !$model->user_type->isIlp;
    }

    public function submit_officer_application(User $user, User $model)
    {
        return $user->id == $model->id && $model->user_type->isIlp && $model->user_type->isVerified && is_null($model->petition);
    }
    
    public function cancel_officer_application(User $user, User $model)
    {
        return $user->id == $model->id && $model->user_type->isIlp && $model->user_type->isVerified && $model->petition && !$model->petition->finished;
    }
    
    public function support_officer_application(User $user, User $model)
    {
        //NOTE: allow own support
        return $model->petition && $model->petition->supporters->count() < 46 
                && !$model->petition->supporters->pluck('id')->contains($user->id);
    }
    
    public function unsupport_officer_application(User $user, User $model)
    {
        return $model->petition && $model->petition->supporters->pluck('id')->contains($user->id);
    }

    public function disqualify_membership(User $user, User $model) 
    {
        if ($user->isAdmin() && $model->user_type->class !== 'user' && !$model->user_type->former) {
            return $this->allow();
        }

        return $this->deny();
    }
    
    public function cancel_guardianship(User $user, User $model) 
    {
        if ($user->isAdmin() && $model->user_type->isIlp && $model->guardianship) {
            return $this->allow();
        }

        return $this->deny();
    }
    
    public function allow_guardianship(User $user, User $model) 
    {
        if ($user->isAdmin() && $model->user_type->isIlp && !$model->guardianship) {
            return $this->allow();
        }

        return $this->deny();
    }
    
    public function withdraw_from_ilp(User $user, User $model) 
    {
        return $user->id == $model->id && $model->user_type->isIlp;
    }
}
