<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Nation;
use App\UserType;
use App\Municipality;
use App\SearchName;
use App\Community;
use Illuminate\Support\Facades\DB;
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
    protected $redirectTo = '/main';

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
            'name' => ['required', 'string', 'regex:/^[a-z\d ]+$/i', 'max:92'],
            'nation' => ['required', 'string', 'regex:/^[a-z\d ]+$/i', 'max:92'],
            'reg_email' => ['required', 'string', 'email', 'max:92', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'max:191'],
            'age_verification' => ['accepted'],
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

        // filter errors for USA
        
        if ($search = replace_nation_USA($data['nation'])) {
            $nation = Nation::where('title', $search)->first();
        } else {
            $nation = Nation::where(\DB::raw('BINARY `title`'), $data['nation'])->first();
        }
        
        if (is_null($nation)) {
            $nation = Nation::create([
               'title' => $data['nation']
            ]);
        }
        
        $value = \App\Setting::where('name', 'auto_validation')->first()->value;        
        $user_type = UserType::where('class', 'user')
            ->where('candidate', 0)
            ->where('former', 0)
            ->where('verified', ($value? 1:0))
            ->first();
        
        $user->nation()->associate($nation);
        $user->user_type()->associate($user_type);
        
        if ($municipality = Municipality::where('title', 'Name of my town here')->first()) {
            $user->municipality()->associate($municipality);
        }
        
        $user->save();

        $search_name = new SearchName();        
        $search_name->name = $this->gen_search_name($data['name']);
        $search_name->hash = base64_encode(Hash::make($search_name->name));
        $search_name->seachable = 1;
        $search_name->active = 1;
        
        $search_name->user()->associate($user);
        $search_name->save();

        $ids = \DB::table('communities')->where('on_registration', true)->get()->pluck('id');
        $user->communities()->sync($ids);
        
        foreach( communities_list() as $order => $title) {
            $community = Community::where('title', $title)->first();
            
            if ($community) {
                $affected = DB::table('community_user')
                    ->where('community_id', $community->id)
                    ->where('user_id', $user->id)
                    ->update(['order' => $order]);
            }
        }
        
        $user->disqualifying_users()->syncWithoutDetaching($user);
        
        $default_leads = User::whereHas('default_lead')->get();
        $user->following()->sync($default_leads);

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
