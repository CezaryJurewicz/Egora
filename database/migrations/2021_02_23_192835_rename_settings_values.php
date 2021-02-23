<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSettingsValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('settings')
              ->where('name', 'verified_at_registration')
              ->update(['name' => 'auto_validation']);
        
        DB::table('settings')
              ->where('name', 'message')
              ->update(['name' => 'information']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')
              ->where('name', 'auto_validation')
              ->update(['name' => 'verified_at_registration']);
        
        DB::table('settings')
              ->where('name', 'information')
              ->update(['name' => 'message']);
        
    }
}
