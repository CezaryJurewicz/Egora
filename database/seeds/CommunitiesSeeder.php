<?php

use Illuminate\Database\Seeder;
use App\User;

class CommunitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = [];
        
        $ids[] = DB::table('communities')->insertGetId([
            'title' => 'Truths we should all know',
            'on_registration' => true,
            'quit_allowed' => false
        ]);

        $ids[] = DB::table('communities')->insertGetId([
            'title' => 'Stories we should all hear',
            'on_registration' => true,
            'quit_allowed' => false
        ]);
        
        $ids[] = DB::table('communities')->insertGetId([
            'title' => 'Egora Development Ideas',
            'on_registration' => true,
            'quit_allowed' => false
        ]);
        
        $users = User::get();
        foreach ($users as $user ){
            $user->communities()->toggle($ids);
        }
    }
}
