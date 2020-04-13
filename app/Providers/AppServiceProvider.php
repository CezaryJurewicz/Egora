<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        Validator::extend('unique_position', function($attribute, $value, $parameters)
        {
            return DB::table('idea_user')
                ->where('order', $value)
                ->where('user_id', $parameters[0])
                ->count()<1;
        });
    }
}
