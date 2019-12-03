<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('petitions', function(Blueprint $table) {
            $table->string('polis')->after('user_id');
            $table->boolean('finished')->default(0)->after('polis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('petitions', function(Blueprint $table) {
            $table->dropColumn('finished');
            $table->dropColumn('polis');
        });
    }
}
