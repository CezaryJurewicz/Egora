<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCommunitiesAddBookmarksLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communities', function(Blueprint $table) {
            $table->smallInteger('bookmark_limit')->unsigned(true)->default(100)->after('order');
        });  
        
        DB::table('communities')
            ->whereIn('title', ["Truths we all should know", "Stories we all should hear", "My favorite books", "My favorite films", "My favorite recipes"])
            ->update(['bookmark_limit' => 300]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communities', function(Blueprint $table) {
            $table->dropColumn('bookmark_limit');
        });
    }
}
