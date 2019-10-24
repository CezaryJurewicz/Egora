<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('rank_users', function (Blueprint $table) {
            $table->bigInteger('rank_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->foreign('rank_id')->references('id')
                    ->on('ranks')->onDelete('cascade');
            
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
        Schema::dropIfExists('rank_users');
        Schema::dropIfExists('ranks');
    }
}
