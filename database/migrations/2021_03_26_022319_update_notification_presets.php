<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\NotificationPreset;

class UpdateNotificationPresets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_presets', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('title');
        });
        
        $presets = NotificationPreset::all();
        foreach ($presets as $p) {
            $p->order = $p->id+1;
            $p->save();
        }
        
        $preset = new NotificationPreset();
        $preset->title = 'Thank you so much for this idea! I will share it far and wide!';
        $preset->order = 1;
        $preset->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_presets', function (Blueprint $table) {
            $table->dropColumn('order');
        });        
    }
}
