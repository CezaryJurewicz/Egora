<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTypesChangeTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('user_types')
                ->where('title', 'Unverified User')
                ->update(['title' => 'Unverified']);
        
        DB::table('user_types')
                ->where('title', 'Verified User')
                ->update(['title' => 'Verified']);
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
