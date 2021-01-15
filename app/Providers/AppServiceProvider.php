<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

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
        URL::forceScheme(env('FORCE_SCHEME', 'https'));

        Schema::defaultStringLength(191);
        
        Validator::extend('unique_position', function($attribute, $value, $parameters)
        {
            return DB::table('idea_user')
                ->where('order', $value)
                ->where('user_id', $parameters[0])
                ->count()<1;
        });
        
        View::composer('*', function ($view)
        {
            $view->with('current_egora', session('current_egora', 'default'));
        });
        
    }
}
