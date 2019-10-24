<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->softDeletes();
            
            $table->bigInteger('nation_id')->unsigned();
            $table->bigInteger('user_type_id')->unsigned();
            
            $table->foreign('nation_id')->references('id')
                    ->on('nations')->onDelete('cascade');
            
            $table->foreign('user_type_id')->references('id')
                    ->on('user_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('nation_id');
            $table->dropColumn('user_type_id');
            $table->dropColumn('deleted_at');
        });
    }
}
