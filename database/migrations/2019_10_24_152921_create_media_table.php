<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->nullableMorphs('mediable','media_mediable_idx');
            $table->string('original_name');
            $table->string('filename');
            $table->string('disk',50)->default('local');
            $table->text('url')->nullable();
            $table->string('mime')->nullable();
            $table->string('hash')->nullable();
            $table->timestamps();
            
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
        Schema::dropIfExists('media');
    }
}
