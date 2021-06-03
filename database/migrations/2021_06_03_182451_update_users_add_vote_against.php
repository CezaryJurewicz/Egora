<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;

class UpdateUsersAddVoteAgainst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::all();
        foreach($users as $user) {
            $user->disqualifying_users()->syncWithoutDetaching($user);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $users = User::all();
        foreach($users as $user) {
            $user->disqualifying_users()->detach($user->id);
        }
    }
}
