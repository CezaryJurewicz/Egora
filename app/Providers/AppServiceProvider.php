<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Notification as NotificationModel;
use App\CommentNotification;

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
        
        View::composer('layouts.app', function ($view)
        {
            $view->with('current_egora', session('current_egora', 'default'));
            
            // TODO: rewrite..
            if (auth('web')->check()) {
                $notifications = NotificationModel::with('idea')
                    ->where(function($q){
                        $q->whereHas('receiver', function($q) {
                            $q->where('id', auth('web')->user()->id);
                        })
                        ->whereHas('idea', function($q) {
                            $q->where('egora_id', current_egora_id());
                        });
                    })->new()->get();

                $notification_ids = $notifications->pluck('id');
                $responses = NotificationModel::where(function($q) use ($notifications) {
                    $q->whereHas('sender', function($q) {
                        $q->where('id', auth('web')->user()->id);
                    });
                    $q->whereHas('idea', function($q) use ($notifications) {
                        $q->whereIn('id', $notifications->pluck('idea.id'));
                    });
                    $q->where(function($q) use ($notifications){
                        $q->whereIn('notification_id', $notifications->pluck('id'));
                    });
                })->withTrashed()->new()->get();
                
                $inbox_notifications = $notification_ids->diff($responses->pluck('notification_id'));

                $view->with('inbox_notifications_cnt', $inbox_notifications->count());

                $comment_notifications = CommentNotification::where(function($q){
                        $q->whereHas('receiver', function($q) {
                            $q->where('id', auth('web')->user()->id);
                        })
                        ->where('egora_id', current_egora_id());
                    })->select('id');
                    
                $view->with('inbox_comment_notifications_cnt', $comment_notifications->count());
                
            }
        });
        
        View::composer(['layouts.app', 'settings.message'], function ($view)
        {
            $value = \App\Setting::where('name', 'information')->first()->value; 
            $view->with('admin_message_text', $value);            
        });
        
    }
}
