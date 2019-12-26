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

    private function _data()
    {
        $total_verified_users = User::whereHas('user_type',function($q){
                $q->where('verified', 1);
            })->get()->count();
            
        $total_verified_ipl_users = User::whereHas('user_type',function($q){
                $q->where('class', '<>' ,'user');
                $q->where('verified', 1);
            })->get()->count();
            
        $group_by_nation = Nation::whereHas('users.user_type', function($q){
                $q->where('class', '<>' ,'user');
                $q->where('verified', 1);
            })
            ->with(['users' => function($q){
                $q->whereHas('user_type', function($q){
                    $q->where('class', '<>' ,'user');
                    $q->where('verified', 1);
                });
            }])->get();
            
        return [$total_verified_users, $total_verified_ipl_users, $group_by_nation];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        list($total_verified_users, $total_verified_ipl_users, $group_by_nation) = $this->_data();
            
        return view('home')->with(compact('total_verified_users', 'total_verified_ipl_users',  'group_by_nation'));
    }
    
    public function indexAdmin()
    {
        list($total_verified_users, $total_verified_ipl_users, $group_by_nation) = $this->_data();
        
        return view('admin.home')->with(compact('total_verified_users', 'total_verified_ipl_users',  'group_by_nation'));
    }
    
    public function welcome()
    {
        return view('welcome');
    }
}
