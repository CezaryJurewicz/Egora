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
    
    public function viewIdi(User $user)
    {
        return (auth('web')->user() && is_egora());
    }
    
    public function viewIpi(User $user)
    {
        return $user->isGuardian() || (auth('web')->user() && !is_egora());
    }
    
    public function administrate(User $user)
    {
        return $user->isGuardian();
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
        if ($user->isGuardian()) {
            return $this->allow();
        }
        // is not secure for private communities, but preview is also
        // not secure for api
        if (app('request')->has('preview') || app('request')->is('api/*')) {
            return $this->allow();
        }
        
        if (app('request')->has('comment_notification_id')) {
            $comment_notification = \App\CommentNotification::where('id', app('request')->input('comment_notification_id'))
                    ->where('receiver_id', $user->id)->first();
            if ($comment_notification) {
                return $this->allow();
            } else {
                // TODO: need better processing of this part.
                return $this->allow();
            }
        }
        
        if (app('request')->has('bookmark_notification_id')) {
            $bookmark_notification = \App\BookmarkNotification::where('id', app('request')->input('bookmark_notification_id'))
                    ->where('receiver_id', $user->id)->first();
            if ($bookmark_notification) {
                return $this->allow();
            } else {
                // TODO: need better processing of this part.
                return $this->allow();
            }
        }
        
        if (app('request')->has('notification_id') || app('request')->has('invitation_response_notification_id')) {
            $notification = \App\Notification::findOrFail((app('request')->has('notification_id')? app('request')->input('notification_id') : app('request')->input('invitation_response_notification_id')));

            if ($user->id == $notification->receiver->id && $notification->idea_id == $idea->id) {
                $egora = collect(config('egoras'))->first(function($value, $key) use ($notification) {
                    return $value['id'] == $notification->idea->egora_id;
                });

//                request()->session()->put('current_egora', $egora['name']);
                return $this->allow();
            }
        }
        
        if (is_egora('community')) {
            return request()->has('notification_id') || $user->communities->contains($idea->community); // TODO: add checks
        }  
        
        return $idea->egora_id == current_egora_id();
    }
    
    public function bookmark(User $user, Idea $idea)
    {
        
        return $this->allow();
    }
    
    public function like(User $user, Idea $idea)
    {
        if (is_null($idea->community) && is_null($idea->municipality)) {        
            if ($user->user_type->class == 'user' && $idea->nation->title=='Egora') {
                return $this->deny();
            }

            $nations = Nation::whereIn('title', ['Egora', 'Universal', $user->nation->title])->get()->pluck('id');

            if (!$nations->contains($idea->nation->id)) {
                return $this->deny();
            }
        }
        
        if (!is_null($idea->community)) {
            if (!$user->communities->contains($idea->community)) {
                return $this->deny();
            }
        }
        
        if (!is_null($idea->municipality)) {
            if ($idea->municipality && $user->municipality->id != $idea->municipality->id) {
                return $this->deny();
            }
        }
        return $this->allow();
    }
    
    public function comment(User $user, Idea $idea)
    {
        return ($user->user_type->isVerified && ($idea->comments->where('user_id',$user->id)->count() < 23));
    }
    
    public function invite_examine(User $user, Idea $idea, $notification) 
    {
        return (!isset($notification));
        
        // only allow invite for ideas in IP
        // return $user->liked_ideas->contains($idea) && (!isset($notification));
    }
    
    public function invite_response(User $user, Idea $idea) 
    {
//      $user->user_received_notifications()->where('idea_id',$idea->id)->first();
        return $user->user_received_notifications->pluck('pivot.idea_id')->contains($idea->id);
    }
    
    public function unlike(User $user, Idea $idea)
    {
        return $this->allow();
    }

    public function move(User $user, Idea $idea, User $model)
    {
        return $user->liked_ideas->contains($idea) && $model->id == $user->id;
    }
    
    public function bookmark_move(User $user, Idea $idea, User $model)
    {
        return $user->bookmarked_ideas->contains($idea) && $model->id == $user->id;
    }
    
    /**
     * Determine whether the user can create ideas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if(is_egora('municipal') && is_null($user->municipality_id)) {
            return $this->deny();
        }
        
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
