<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->boolean('on_registration')->default(false);
            $table->boolean('quit_allowed')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('community_user', function (Blueprint $table) {
            $table->bigInteger('community_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->foreign('community_id')->references('id')
                    ->on('communities')->onDelete('cascade');
            
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
        });
        
        Artisan::call('db:seed', [
            '--class' => 'CommunitiesSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_user');
        Schema::dropIfExists('communities');
    }
}
