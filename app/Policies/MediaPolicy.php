<?php

namespace App\Policies;

use App\Media;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class MediaPolicy
{
    use HandlesAuthorization, PolicyTrait;

    /**
     * Determine whether the user can view any media.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the media.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function view(User $user, Media $media)
    {
        //
    }

    /**
     * Determine whether the user can create media.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the media.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function update(User $user, Media $media)
    {
        //
    }

    /**
     * Determine whether the user can delete the media.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function delete(User $user, Media $media)
    {
        return $media->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the media.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function restore(User $user, Media $media)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the media.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function forceDelete(User $user, Media $media)
    {
        //
    }
}
