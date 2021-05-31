<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGuardianshipField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('guardianship')->default(0)->change();
        });
        
        App\User::where('guardianship', 1)->update(['guardianship' => 0]);
        App\Admin::where('guardianship', 1)->update(['guardianship' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('guardianship')->default(1)->change();
        });
        
    }
}
