<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('contacts', 230)->change();
        });
        
        Schema::table('meetings', function(Blueprint $table) {
            $table->string('topic', 230)->change();
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
            $table->string('contacts', 255)->change();
        });
        
        Schema::table('meetings', function(Blueprint $table) {
            $table->string('topic', 255)->change();
        });
    }
}
