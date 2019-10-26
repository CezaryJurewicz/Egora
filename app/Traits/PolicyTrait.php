<?php

namespace App\Traits;

trait PolicyTrait {

    /**
     * Determine whether the user can view any events.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return $this->allow();
        }
    }
}