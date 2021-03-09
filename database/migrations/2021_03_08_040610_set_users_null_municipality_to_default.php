<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Municipality;
use App\User;

class SetUsersNullMunicipalityToDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($municipality = Municipality::where('title', 'Name of my town here')->first()) {
            $users = User::whereNull('municipality_id')->get();
            
            foreach($users as $user) {
                $user->municipality()->associate($municipality);
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
