<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

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
}
