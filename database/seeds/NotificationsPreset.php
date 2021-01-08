<?php

use Illuminate\Database\Seeder;

class NotificationsPreset extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        DB::table('notification_presets')->insertOrIgnore([[
            'title' => "Let's discuss this idea!",
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'title' => 'I will rework this and get back to you.',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'title' => 'This idea requires too much revisioning to salvage the good parts.',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'title' => "I have a different approach to solving our problems. We should have a deeper "
            . "discussion after we have studied each other's Ideological Profiles.",
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'title' => 'I prioritize other issues more highly.',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'title' => 'This idea does not pertain to me. There is nothing I can do with it.',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'title' => 'It is logically impossible for hateful ideas to succeed in Egora.',
            'created_at' => $now,
            'updated_at' => $now
        ]]);
    }
}
