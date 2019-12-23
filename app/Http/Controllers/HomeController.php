<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Nation;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_verified_users = User::whereHas('user_type',function($q){
                $q->where('verified', 1);
            })->get()->count();
            
        $total_verified_ipl_users = User::whereHas('user_type',function($q){
                $q->where('class', '<>' ,'user');
                $q->where('verified', 1);
            })->get()->count();
            
        $group_by_nation = Nation::whereHas('users')
            ->with(['users' => function($q){
                $q->whereHas('user_type', function($q){
                    $q->where('verified', 1);
                    $q->where('former', 0);
                });
            }])->get();
            
        return view('home')->with(compact('total_verified_users', 'total_verified_ipl_users',  'group_by_nation'));
    }
    
    public function indexAdmin()
    {
        return view('admin.home');
    }
    
    public function welcome()
    {
        return view('welcome');
    }
}
