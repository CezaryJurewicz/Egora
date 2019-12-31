<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Nation;
use App\UserType;
use App\SearchName;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'nation' => ['required', 'string', 'max:255'],
            'reg_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {        
        $user = new User([
            'name' => $data['name'],
            'email' => $data['reg_email'],
            'password' => Hash::make($data['password'])
        ]);

        $nation = Nation::where(DB::raw('BINARY `title`'), $data['nation'])->first();
                
        if (is_null($nation)) {
            $nation = Nation::create([
               'title' => $data['nation']
            ]);
        }
        if (env('VALID_AT_REG', false)) {
            $user_type = UserType::where('title', 'Verified User')->first();
        } else {
            $user_type = UserType::where('title', 'Unverified User')->first();
        }
        
        if (is_null($user_type)) {
            $user_type = UserType::create([
               'title' => 'Unverified User'
            ]);
        }

        $user->nation()->associate($nation);
        $user->user_type()->associate($user_type);
        
        $user->save();

        $search_name = new SearchName();        
        $search_name->name = $this->gen_search_name($data['name']);
        $search_name->seachable = 1;
        $search_name->active = 1;
        
        $search_name->user()->associate($user);
        $search_name->save();

        return $user;
    }
    
    private function gen_search_name($name) 
    {
        $i = 1;
        do {
            $new_name = $name.' '.$i;
            $search_name = SearchName::where('name', $new_name)->get();
            $i++;
        } while($search_name->isNotEmpty());
        
        return $new_name;
    }
}
