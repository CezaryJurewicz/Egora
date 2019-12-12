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
use App\Notifications\UserEmailChange;
use App\Notifications\UserEmailChanged;

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
        $officer = null;
        $officer_petitioner = null;
        
        $nations = Nation::get();
        
        if ($request->exists('search_name')){     
            $validator = Validator::make($request->all(),[
                'search_name' => 'nullable|min:3|string|required_without_all:officer_petitioner,officer',
                'nation' => 'nullable|exists:nations,title',
                'officer' => 'nullable|boolean',
                'officer_petitioner' => 'nullable|boolean',
            ]); 

            if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }
            
            $search_name = $request->input('search_name');
            $nation = $request->input('nation');
            $officer = $request->input('officer');
            $officer_petitioner = $request->input('officer_petitioner');
            
            $model = User::query();
            
            if ($search_name) {
                $model->whereHas('search_names', function($q) use ($request){
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
            }
            
            if ($officer) {
                $model->orWhere(function($q) use ($request) {
                    $q->whereHas('user_type', function($q) {
                        $q->where('class','officer');
                    });
                    
                    if ($request->input('nation')) {
                        $q->whereHas('nation', function($q) use ($request){
                            $q->where('title', $request->input('nation'));
                        });
                    }
                });
            }
            
            if ($officer_petitioner) {
                $model->orWhere(function($q) use ($request) {
                    $q->whereHas('user_type', function($q) {
                        $q->where('class','petitioner');
                    });
                    
                    if ($request->input('nation')) {
                        $q->whereHas('nation', function($q) use ($request){
                            $q->where('title', $request->input('nation'));
                        });
                    }
                });
            }
            
            
            $users = $model->paginate(10);
            
        } else {
            $users = collect();
        }
        
        return view('users.search')->with(compact('users', 'nations', 'search_name', 'nation', 'officer', 'officer_petitioner'));
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
            $q->whereHas('user');
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
            'contacts' => ['nullable', 'string', 'max:255'],
            'nation' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed']
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $user->name = $request->name;
        $user->contacts = $request->contacts;
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

    public function update_password(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'current' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ( Hash::check($request->current, $user->password) ) {
        
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('users.settings', $request->user()->id)->with('success', 'Password updated!');   
        }
        
        return redirect()->route('users.settings', $request->user()->id)->withErrors('Current password doesn\'t match!');
    }
    
    public function update_privacy(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'seachable' => ['required', 'boolean'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        $searchName = $request->user()->active_search_names->first();
        
        $searchName->seachable = $request->input('seachable')?:0;
        $searchName->save();

        return redirect()->back()->with('success', 'Privacy updated.');       
    }

    public function deactivate(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'deactivate' => ['required', 'boolean'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        
        if ($request->input('deactivate')) 
        {
            Auth::logout();
            
            $user->delete();
            return redirect()->back()->with('success', 'User deactivated.');  
        }            
        
        return redirect()->back()->withErrors(['Deactivate field is missing']);
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
    
    public function follow(Request $request, User $user)
    {
        $user->followers()->syncWithoutDetaching($request->user());
        return redirect()->back()->with('success', 'Added follower');  
    }
    
    public function unfollow(Request $request, User $user)
    {
        $user->followers()->detach($request->user()->id);
        return redirect()->back()->with('success', 'Removed follower');  
    }
    
    public function settings(User $user)
    {
        return view('users.settings')->with(compact('user'));
    }
    
    public function disqualify_membership (Request $request, User $user) 
    {
        $type = UserType::where('class', 'member')
                ->where('verified', $user->user_type->verified)
                ->where('former', 1)
                ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        return redirect()->back()->with('success', 'Member disqualified.');  
    }
    
    public function cancel_guardianship(Request $request, User $user) 
    {
        $user->guardianship = false;
        $user->save();
        
        return redirect()->back()->with('success', 'Guardianship disabled.');          
    }
    
    public function allow_guardianship(Request $request, User $user) 
    {
        $user->guardianship = true;
        $user->save();
        
        return redirect()->back()->with('success', 'Guardianship enabled.');          
    }
    
    public function update_email_send_token(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ($request->email !== $user->email) {
            $user->another_email = $request->email;
            $user->email_token = \Illuminate\Support\Str::random(60);

            if ($user->save()) {
                $user->notify(new UserEmailChange());

                return redirect()->back()->with('success', 'Message sent, please check your current email.');   
            }
            
        } else {
            return redirect()->back()->withErrors('Write new email in a box');   
        }
        
        return redirect()->back()->withErrors('Email update failed!');        
    }

    public function update_email(Request $request, string $token) 
    {
        $user = User::where('id', $request->user()->id)
                ->where('email_token', $token)->first();
        
        if ($user) {
            $user->email = $user->another_email;
            $user->another_email = null;
            $user->email_token = null;
            $user->save();

            $user->notify(new UserEmailChanged());
            
            return redirect()->route('users.settings', $user->id)->with('success', 'Email changed.');
        }
        
        return redirect()->back()->withErrors('Incorrect signed user and email token.');
    }
    
    public function update_email_confirm(Request $request, string $token) 
    {
        $user = User::where('id', $request->user()->id)
                ->where('email_token', $token)->first();
        
        if ($user) {
            return view('users.update_email_confirm')->with(compact('user', 'token'));
        }
        
        return redirect()->back()->withErrors('Incorrect signed user and email token.');
    }    
}
