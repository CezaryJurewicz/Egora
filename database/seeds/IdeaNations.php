<?php

use Illuminate\Database\Seeder;
use App\Nation;


class IdeaNations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Nation::create([
            'title' => 'Universal'
        ]);
        
        Nation::create([
            'title' => 'Egora'
        ]);
    }
}
