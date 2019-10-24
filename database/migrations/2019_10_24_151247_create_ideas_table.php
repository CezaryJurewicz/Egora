<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('position');
            
            $table->bigInteger('nation_id')->unsigned()->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
            $table->foreign('nation_id')->references('id')
                    ->on('nations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ideas');
    }
}
