<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdeasTableChangeForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable(false)->change();
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
        });
    }
}
