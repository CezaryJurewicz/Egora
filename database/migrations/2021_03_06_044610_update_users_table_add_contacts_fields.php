<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddContactsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->renameColumn('contacts', 'other_1');
            $table->string('email_address', 92)->nullable();
            $table->string('phone_number', 92)->nullable();
            $table->string('social_media_1', 92)->nullable();
            $table->string('social_media_2', 92)->nullable();
            $table->string('messenger_1', 92)->nullable();
            $table->string('messenger_2', 92)->nullable();
            $table->string('other_2', 230)->nullable();
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
            $table->renameColumn('other_1', 'contacts');
            $table->dropColumn('email_address');
            $table->dropColumn('phone_number');
            $table->dropColumn('social_media_1');
            $table->dropColumn('social_media_2');
            $table->dropColumn('messenger_1');
            $table->dropColumn('messenger_2');
            $table->dropColumn('other_2');
        });
    }
}
