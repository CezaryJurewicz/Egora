<?php
namespace App\Http\Middleware;

use Closure;

class LastOnlineAt
{
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $next($request);
        }
        
        if (!auth()->user()->isAdmin() && auth()->user()->last_online_at->diffInHours(now()) !==0)
        { 
            $user = auth()->user();
            $user->last_online_at = now();
            $user->save();
        }
        
        return $next($request);
    }
}