<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusesTableAddFromId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('updates', function(Blueprint $table) {
            $table->bigInteger('from_id')->unsigned()->nullable();
            
            $table->foreign('from_id')->references('id')
                    ->on('users')->onDelete('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('updates', function(Blueprint $table) {
            $table->dropForeign(['from_id']);
            $table->dropColumn('from_id');
        });
    }
}
