<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalityUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('users', function(Blueprint $table) {
            $table->bigInteger('municipality_id')->unsigned()->nullable()->after('nation_id');
            
            $table->foreign('municipality_id')->references('id')
                    ->on('municipalities')->onDelete('cascade');
        });
        
        Schema::table('ideas', function(Blueprint $table) {
            $table->bigInteger('municipality_id')->unsigned()->nullable()->after('nation_id');
            
            $table->foreign('municipality_id')->references('id')
                    ->on('municipalities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ideas', function(Blueprint $table) {
            $table->dropForeign(['municipality_id']);
            $table->dropColumn('municipality_id');
        });
        
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign(['municipality_id']);
            $table->dropColumn('municipality_id');
        });
        
        Schema::dropIfExists('municipalities');
    }
}
         