<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampagesTableAddSubdivisionField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function(Blueprint $table) {
            $table->bigInteger('subdivision_id')->unsigned()->nullable();
            $table->integer('order')->default(0);
            
            $table->foreign('subdivision_id')->references('id')
                    ->on('subdivisions')->onDelete('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function(Blueprint $table) {
            $table->dropForeign(['subdivision_id']);
            $table->dropColumn('subdivision_id');
            $table->dropColumn('order');
        });
    }
}
