<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
        });
        
        // Supporters
        Schema::create('petition_users', function (Blueprint $table) {
            $table->bigInteger('petition_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->foreign('petition_id')->references('id')
                    ->on('petitions')->onDelete('cascade');
            
            $table->foreign('user_id')->references('id')
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
        Schema::dropIfExists('petition_users');
        Schema::dropIfExists('petitions');
    }
}
