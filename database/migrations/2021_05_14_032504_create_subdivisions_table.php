<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubdivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdivisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->index();
            $table->bigInteger('nation_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('nation_id')->references('id')
                    ->on('nations')->onDelete('cascade');            
        });
        
        Schema::create('subdivision_user', function (Blueprint $table) {
            $table->bigInteger('subdivision_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('order')->default(0);
            
            $table->foreign('subdivision_id')->references('id')
                    ->on('subdivisions')->onDelete('cascade');
            
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
        Schema::dropIfExists('subdivision_user');
        Schema::dropIfExists('subdivisions');
    }
}
