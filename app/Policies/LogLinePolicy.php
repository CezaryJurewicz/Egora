<?php

namespace App\Policies;

use App\LogLine;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogLinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any log lines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can view the log line.
     *
     * @param  \App\User  $user
     * @param  \App\LogLine  $logLine
     * @return mixed
     */
    public function view(User $user, LogLine $logLine)
    {
        //
    }

    /**
     * Determine whether the user can create log lines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the log line.
     *
     * @param  \App\User  $user
     * @param  \App\LogLine  $logLine
     * @return mixed
     */
    public function update(User $user, LogLine $logLine)
    {
        //
    }

    /**
     * Determine whether the user can delete the log line.
     *
     * @param  \App\User  $user
     * @param  \App\LogLine  $logLine
     * @return mixed
     */
    public function delete(User $user, LogLine $logLine)
    {
        //
    }

    /**
     * Determine whether the user can restore the log line.
     *
     * @param  \App\User  $user
     * @param  \App\LogLine  $logLine
     * @return mixed
     */
    public function restore(User $user, LogLine $logLine)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the log line.
     *
     * @param  \App\User  $user
     * @param  \App\LogLine  $logLine
     * @return mixed
     */
    public function forceDelete(User $user, LogLine $logLine)
    {
        //
    }
}
