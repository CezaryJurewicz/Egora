<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\NotificationPreset;

class UpdateNotificationPresetsAddOne extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $presets = NotificationPreset::all();
        foreach ($presets as $p) {
            $p->order = $p->order * 10;
            $p->save();
        }
        
        $preset = App\NotificationPreset::where('title','This idea does not pertain to me. There is nothing I can do with it.')->first();
        
        $newpreset = new NotificationPreset();
        $newpreset->id = $presets->count() + 1;
        $newpreset->title = 'This is unnecessary. You are stating the obvious.';
        $newpreset->order = $preset->order + 1;
        $newpreset->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        App\NotificationPreset::where('title','This is unnecessary. You are stating the obvious.')->delete();
        
        $presets = NotificationPreset::all();
        foreach ($presets as $p) {
            $p->order = $p->order / 10;
            $p->save();
        }
    }
}
