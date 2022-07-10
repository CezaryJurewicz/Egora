<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersAddOfficeHoursFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->text('office_hours')->nullable();
            $table->string('time_zone', 46)->nullable();
            $table->string('meeting_location', 92)->nullable();
            $table->string('calendar_link', 92)->nullable();
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
            $table->dropColumn('office_hours');
            $table->dropColumn('time_zone');
            $table->dropColumn('meeting_location');
            $table->dropColumn('calendar_link');
        });
    }
}
