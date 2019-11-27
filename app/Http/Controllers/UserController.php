<?php

namespace App\Http\Controllers;

use App\User;
use App\Nation;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Events\UserNameChanged;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withTrashed()->get();
        
        return view('users.index')->with(compact('users'));
    }

    public function search(Request $request)
    {        
        $search_name = null;
        $nation = null;
        
        $nations = Nation::get();
        
        if ($request->exists('search_name')){     
            $validator = Validator::make($request->all(),[
                'search_name' => 'required|min:3|string',
                'nation' => 'nullable|exists:nations,title',
            ]); 

            if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }
            
            $search_name = $request->input('search_name');
            $nation = $request->input('nation');
            
            $model = User::whereHas('search_names', function($q) use ($request){
                $q->where(function($q) use ($request){
                    $q->where('name','like', $request->input('search_name').'%');
                    $q->where('seachable','1');
                    $q->where('active','1');
                });
                $q->orWhere(function($q) use ($request){
                    $q->where('name','like', $request->input('search_name'));
                    $q->where('seachable','0');
                    $q->where('active','1');
                });
            });
            
            if ($nation) {
                $model->whereHas('nation', function($q) use ($request){
                    $q->where('title', $request->input('nation'));
                });
            }
            
            $users = $model->paginate(10);
            
        } else {
            $users = collect();
        }
        
        return view('users.search')->with(compact('users', 'nations', 'search_name', 'nation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.view')->with(compact('user'));
    }
    
    public function ideological_profile(User $user)
    {
        $user->load(['liked_ideas' => function($q){
            $q->with(['nation', 'liked_users' => function($q){
                $q->whereHas('user_type',function($q){
                    $q->where('verified', 1);
                });
            }]);
        }]);

        return view('users.ideological_profile')->with(compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with(compact('user'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'nation' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed']
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $nation = Nation::where('title', $request->nation)->first();
                
        if (is_null($nation)) {
            $nation = Nation::create([
               'title' => $request->nation
            ]);
        }
        
        if ($user->nation->id !== $nation->id) {
            $user->load(['liked_ideas' => function($q) use ($user){
                $q->whereHas('nation', function($q) use ($user){
                    $q->where('id', $user->nation->id);
                });
            }]);
            $ids = $user->liked_ideas->pluck('id')->toArray();
            
            $user->liked_ideas()->toggle($ids);
        }
        
        $user->nation()->associate($nation);
        
        if($user->save()){
            event(new UserNameChanged($user));
            return redirect()->route('users.ideological_profile', $request->user()->id)->with('success', 'User information updated!');   
        }
        
        return redirect()->route('users.ideological_profile', $request->user()->id)->withErrors('User information update failed!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if (!($request->user() instanceof \App\Admin)) {        
            Auth::logout();
        }
        
        if (!$user->user_type->verified) {
            $user->forceDelete();
            return redirect()->route('index')->with('success', 'User permanently deleted');  
        } else {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted');  
        }
        
        return redirect()->back()->withErrors(['User deletion error']);
    }
    
    public function restore(User $user) 
    {
        $user->restore();
        
        return redirect()->back()->with('success', 'User restored');  
    }
    
    public function verify(User $user)
    {
        $type = UserType::where('class', $user->user_type->class)
                ->where('candidate', $user->user_type->candidate)
                ->where('verified', 1)
                ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        return redirect()->back()->with('success', 'User verification updated!');  
    }
    
    public function unverify(User $user)
    {
        $type = UserType::where('class', $user->user_type->class)
                ->where('candidate', $user->user_type->candidate)
                ->where('verified', 0)
                ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        return redirect()->back()->with('success', 'User verification updated!');  
    }
}
