<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Nation;
use App\Community;

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
                $q->where('former', 0);
                $q->where('verified', 1);
            })->get()->count();
            
        $group_by_nation = Nation::whereHas('users.user_type', function($q){
                $q->where('class', '<>' ,'user');
                $q->where('former', 0);
                $q->where('verified', 1);
            })
            ->withCount(['users' => function($q){
                $q->whereHas('user_type', function($q){
                    $q->where('class', '<>' ,'user');
                    $q->where('former', 0);
                    $q->where('verified', 1);
                });
            }])
            ->with(['users' => function($q){
                $q->whereHas('user_type', function($q){
                    $q->where('class', '<>' ,'user');
                    $q->where('former', 0);
                    $q->where('verified', 1);
                });
            }])
            ->orderBy('users_count', 'desc')
            ->orderBy('title', 'asc')
            ->get();
            
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
            
        return view('egoras.home')->with(compact('total_verified_users', 'total_verified_ipl_users',  'group_by_nation'));
    }
    
    public function community(Request $request)
    {
        $user = $request->user();
        $user->load('communities');
        
        return view('egoras.community')->with(compact('user'));
    }
    
    public function municipal()
    {
        return view('egoras.municipal');
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
