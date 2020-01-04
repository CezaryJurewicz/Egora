<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMeetingsTableChangeAddressField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meetings', function(Blueprint $table) {
            $table->string('address', 230)->change();
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetings', function(Blueprint $table) {
            $table->text('address')->change();
        });   
    }
}
