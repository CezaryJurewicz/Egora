<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;
use Illuminate\Support\Facades\DB;

class AddDefaultMunicipality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $id = DB::table('municipalities')->insertGetId([
            'title' => 'Name of my town here'
        ]);
        
        $users = User::get();
        foreach ($users as $user){
            if (is_null($user->municipality)) {
                $user->municipality()->associate($id);
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
