<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/main';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:web')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    protected function redirectPath() {
        return route('ideas.indexes', ['sort'=>'date']);
    }


    protected function attemptLogin(\Illuminate\Http\Request $request)
    {
        if ($admin = \Auth::guard('admin')->attempt(
            $this->credentials($request), $request->filled('remember')
        )) {
            return $admin;
        }
        
        if ($user = \Auth::guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        )) {
            if ($usr = $this->guard()->user()) {
                $usr->disappeared = 1;
                $usr->save();
                
                $searchName = $usr->search_names->first();
                $searchName->active = 1;
                $searchName->save();
            }
            
            return $user;
        }
    }       
    
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        if (\Auth::guard('admin')->check()) {
            return \Auth::guard('admin');
        }
        
        return \Auth::guard();
    }
    
    protected function loggedOut(Request $request) {
        return redirect()->route('index');
    }
    
    public function showLoginForm()
    {
        return view('auth.register');
    }
}
