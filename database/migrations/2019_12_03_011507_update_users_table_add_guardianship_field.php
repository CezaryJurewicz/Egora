<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddGuardianshipField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('guardianship')->default(1);
        });
        
        Schema::table('admins', function(Blueprint $table) {
            $table->boolean('guardianship')->default(1);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('guardianship');
        });
        
        Schema::table('admins', function(Blueprint $table) {
            $table->dropColumn('guardianship');
        });
    }
}
