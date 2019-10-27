<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTypeSubtitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_types', function(Blueprint $table) {
            $table->softDeletes();
            $table->string('subtitle')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_types', function(Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('deleted_at');
        });
    }
}
