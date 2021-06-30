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
use App\Events\BeforeUserNationChanged;
use App\Events\SearchNameChanged;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Events\UserLostVerification;
use App\Events\UserLeftIlp;
use App\SearchName;
use App\Idea;
use App\Notification as NotificationModel;
use App\Events\UserInvitedToIdea;
use App\Events\UserLeftComminity;
use App\Municipality;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\UserLeftingMunicipality;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = null;
        
        $model = User::withTrashed();
        
        if ($request->has('search')) {
            $model->where(function($q) use($request){
                $q->where('id', (int) $request->search);
                $q->orWhere('name', 'like', $request->search.'%');
                $q->orWhere('email', 'like', '%'.$request->search.'%');
            });
            
            $search = $request->search;
        }

        if ($request->has('awaiting')) {
            $model->where(function($q) use($request){
                $q->whereHas('verification_id');
                $q->whereHas('user_type', function($q){
                    $q->where('verified',0);
                });
            });
        }
        
        $users = $model->orderBy('created_at', 'desc')->paginate(10);
        return view('users.index')->with(compact('users', 'search'));
    }

    public function search(Request $request)
    {        
        $search_name = null;
        $nation = null;
        $officer = null;
        $officer_petitioner = null;
        $recent = false;
        $perPage = 25;
        
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
                    
                    if ($request->input('search_name')) {
                        $q->whereHas('search_names', function($q) use ($request){
                            $q->where(function($q) use ($request){
                                $q->where('name','like', $request->input('search_name').'%');
                                $q->where('active','1');
                            });
                        });
                    }
                });
            } else if ($officer_petitioner) {
                $model->orWhere(function($q) use ($request) {
                    $q->whereHas('user_type', function($q) {
                        $q->where('class','petitioner');
                    });
                    
                    if ($request->input('nation')) {
                        $q->whereHas('nation', function($q) use ($request){
                            $q->where('title', $request->input('nation'));
                        });
                    }
                    
                    if ($request->input('search_name')) {
                        $q->whereHas('search_names', function($q) use ($request){
                            $q->where(function($q) use ($request){
                                $q->where('name','like', $request->input('search_name').'%');
                                $q->where('active','1');
                            });
                        });
                    }                    
                });
            } else  if ($search_name) {
                $model->where(function($q) use ($request) {
                    $q->whereHas('search_names', function($q) use ($request){
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
                });
            
                if ($nation) {
                    $model->whereHas('nation', function($q) use ($request){
                        $q->where('title', $request->input('nation'));
                    });
                }
            }
            
            
            $users = $model->paginate($perPage);
            
        } else {
            $recent = true;
            $users = User::with(['user_type','nation'])
                ->whereHas('search_names', function($q) use ($request){
                    $q->where(function($q) use ($request){
                        $q->where('seachable','1');
                    });
                })->visible()->orderBy('created_at', 'desc')->paginate($perPage);

            $total = 4600;
            
            $users = new LengthAwarePaginator(
                $users->toArray()['data'], 
                $users->total() < $total ? $users->total() : $total, 
                $perPage, 
                LengthAwarePaginator::resolveCurrentPage(), 
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }

        return view('users.search')->with(compact('recent', 'users', 'nations', 'search_name', 'nation', 'officer', 'officer_petitioner'));
    }
    
    public function leads()
    {
        return view('users.leads');
    }

    public function leadsbyid(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        // Change;
        if ($request->user()->isAdmin()) {
            $community_id = $user->communities->first()->id;
        } else {
            $community_id = ($request->has('community_id')?$request->community_id :  $request->user()->communities->first()->id);
        }

        $leads = $user->following()->where('visible',1)->get()->sortBy('active_search_name');
        
        return view('users.leadsbyid')->with(compact('leads', 'user', 'community_id'));
    }
    
    public function communities(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $communities=[];
        
        $dec = count($user->communities_not_allowed_to_leave);
        
        foreach( $user->communities_allowed_to_leave as $i=>$community) {
            $communities[$community->pivot->order-$dec-1] = $community;
        }

        return view('users.communities')->with(compact('user', 'communities'));
    }
    
    public function municipality(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        return view('users.municipality')->with(compact('user'));
    }

    
    public function municipality_update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'municipality' => ['required', 'string', 'max:255'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $municipality = Municipality::where('title', $request->municipality)->first();
                
        if (is_null($municipality)) {
            $municipality = Municipality::create([
               'title' => $request->municipality
            ]);
        }
        
        $user = $request->user();
        
        if ($user->municipality->id !== $municipality->id) {
            event(new UserLeftingMunicipality($user));
        }
        
        $user->municipality()->associate($municipality);
        
        if($user->save()){
            return redirect()->route('users.ideological_profile', $request->user()->active_search_name_hash)->with('success', 'Municipality updated!');   
        }
        
        return redirect()->back()->withErrors('Municipality update failed!');   
    }
    
    public function communities_update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'communities' => 'required|array|min:11|max:11',
            'communities.*' => 'nullable|string|min:3|max:190'
        ]); 

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
            
        $before = $request->user()->communities()->get();
        
        $sync_ids =[];
        foreach( $request->user()->communities_not_allowed_to_leave as $i=>$community) {
            $sync_ids[$community->id] = ['order'=>$community->pivot->order];
        }
        
        $inc = count($sync_ids)+1;
        foreach(array_filter($request->communities) as $i=>$community) 
        {
            if ($row = \App\Community::where('title', $community)->first()) {
                $sync_ids[$row->id] = ['order'=>$i+$inc];    
            } else {
                $row = new \App\Community();
                $row->title = $community;
                $row->save();
                $sync_ids[$row->id] = ['order'=>$i+$inc];
            }
        }

        $request->user()->communities()->sync($sync_ids);
        
        $communities_left = $before->diff($request->user()->communities()->get());
        foreach($communities_left as $community) {
            event(new UserLeftComminity($request->user(), $community));
        }
        
        return redirect()->back()->with('success', 'Communities Updated');
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
    
    public function ideological_profile(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $validator = Validator::make($request->all(),[
            'pdf' => ['boolean', 
                function ($attribute, $value, $fail) use ($request, $user) {                    
                    if ($request->user()->id != $user->id)
                    {
                        $fail('You are not allowed to download other users pdf.');
                    }
                }]
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        // Change;
        if ($request->user()->isAdmin()) {
            $community_id = $user->communities->first()->id;
        } else {
            $community_id = ($request->has('community_id')?$request->community_id :  $request->user()->communities->first()->id);
        }
        
        $user->load(['liked_ideas' => function($q) use ($community_id, $user, $request) {
            $q->with('comments.comments');
            if (is_egora('community') && $community_id) {
                if ($request->has('pdf')) {
                    $q->whereNotNull('ideas.community_id');                    
                } else {
                    $q->where('ideas.community_id', $community_id);
                }
            } else {
                $q->whereNull('ideas.community_id');
            }
            
            if (is_egora('municipal')) {
                $q->where('ideas.municipality_id', $user->municipality_id);
                $q->whereNotNull('ideas.municipality_id');
            } else {
                $q->whereNull('ideas.municipality_id');
            }
            
            $q->with(['nation', 'liked_users' => function($q) {
                $q->recent();
                $q->whereHas('user_type',function($q){
                    $q->where('verified', 1);
                });
                
                if (!is_egora()) {
                    //don't include supporters for E,G,O,R,A ideas
                    $q->where('idea_user.order', '>=', 0 );
                }
            }, 'moderators' => function($q) {
                $q->recent();
                $q->whereHas('user_type',function($q){
                    $q->where('verified', 1);
                });
            }]);
            
            //don't include E,G,O,R,A ideas 
            //$q->where('idea_user.order', '>=', 0 );
        }, 'petition.supporters' => function($q) {
            $q->recent();
        }, 'communities' => function($q) use ($request) {
            $q->whereIn('id', $request->user()->communities->pluck('id'));
        }]);

        $ip_score = 0;
        $scores = [];
        foreach($user->liked_ideas as $idea) {
            if ($idea->pivot->order >= 0) {
                $scores[] = $idea->liked_users->pluck('pivot.position')->sum();
            }
        }        
        rsort($scores, SORT_NUMERIC);
        $ip_score = array_sum(array_slice($scores, 0, 23));
        
        if ($request->has('pdf')) {
            view()->share(compact('user', 'community_id', 'ip_score'));
            $pdf_doc = \PDF::loadView('users.ideological_profile_pdf');

//            return $pdf_doc->stream(date('U').'.pdf', array('Attachment'=>0));
            return $pdf_doc->download(date('U').'.pdf');
        }
        
        return view('users.ideological_profile')->with(compact('user', 'community_id', 'ip_score'));
    }
    
    public function about(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $user->load(['petition.supporters' => function($q) {
            $q->recent();
        }]);
        
        return view('users.about')->with(compact('user','hash'));
    }

    public function about_edit(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $user->load(['petition.supporters' => function($q) {
            $q->recent();
        }]);
        
        $content = $user->about_me;
        
        return view('users.about_edit')->with(compact('user','hash', 'content'));
    }
    
    public function about_store(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $validator = Validator::make($request->all(),[
            'content' => ['required', 'string']
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ( $request->has('content') ) {
            $user->about_me = $request->content;
            $user->save();

            return redirect()->route('users.about', $hash)->with('success', 'Information updated!');   
        }
        
        return redirect()->route('users.about', $hash)->withErrors('Information update failed!');
    }
    
    public function profile(Request $request, User $user)
    {
        $user->load(['liked_ideas' => function($q){
            $q->with(['nation', 'liked_users' => function($q){
                $q->recent();
                $q->whereHas('user_type',function($q){
                    $q->where('verified', 1);
                });
            }]);
        }, 'petition.supporters' => function($q) {
            $q->recent();
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
        $searchName = $user->active_search_names->first();
        
        return view('users.edit')->with(compact('user','searchName'));        
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
        $searchName = $user->active_search_names->first();
        
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 
                function ($attribute, $value, $fail) use ($request, $user) {                    
                    if ($request->user()->id == $user->id && !$user->user_type->isOfficer 
                            && !is_null($user->campaign) && $user->name !== $value )
                    {
                        $fail('As a candidate, you are not allowed to change '.$attribute.' currently.');
                    }
                }],
            'current_password' => ['required', 'password'],
            'search_name' => 'required|min:3|string|unique:search_names,name,'.$searchName->id,                        
            'email_address' => ['nullable', 'string', 'max:92'],
            'phone_number' => ['nullable', 'string', 'max:92'],
            'social_media_1' => ['nullable', 'string', 'max:92'],
            'social_media_2' => ['nullable', 'string', 'max:92'],
            'messenger_1' => ['nullable', 'string', 'max:92'],
            'messenger_2' => ['nullable', 'string', 'max:92'],                        
            'other_1' => ['nullable', 'string', 'max:230'],
            'other_2' => ['nullable', 'string', 'max:230'],
            'nation' => ['required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($request, $user) {
                    if ($request->user()->id == $user->id && $user->nation->title !== $value && ($user->user_type->isOfficer || $user->user_type->isPetitioner || !is_null($user->campaign)))
                    {
                        $fail('You are not allowed to change '.$attribute.' currently.');
                    }
                }],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ($searchName->name != $request->input('search_name')) {
            $searchName->name = $request->input('search_name');
            $searchName->hash = base64_encode(Hash::make($request->input('search_name')));
            $searchName->save();
            event(new SearchNameChanged($searchName->user));
        }
        
        if ($user->name != $request->name) {
            $user->name = $request->name;
            event(new UserNameChanged($user));
        }        
        $user->email_address = $request->email_address;
        $user->phone_number = $request->phone_number;
        $user->social_media_1 = $request->social_media_1;
        $user->social_media_2 = $request->social_media_2;
        $user->messenger_1 = $request->messenger_1;
        $user->messenger_2 = $request->messenger_2;
        $user->other_1 = $request->other_1;
        $user->other_2 = $request->other_2;

        $nation = Nation::where('title', $request->nation)->first();
                
        if (is_null($nation)) {
            $nation = Nation::create([
               'title' => $request->nation
            ]);
        }
        
        if ($user->nation->id !== $nation->id) {
            event(new BeforeUserNationChanged($user));
        }
        
        $user->nation()->associate($nation);
        
        if($user->save()){
            return redirect()->route('users.ideological_profile', $request->user()->active_search_names->first()->hash)->with('success', 'User information updated!');   
        }
        
        return redirect()->route('users.ideological_profile', $request->user()->active_search_names->first()->hash)->withErrors('User information update failed!');   
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
    
    public function update_notifications(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'notifications' => ['required', 'boolean'],
            'сomment_notifications' => ['required', 'boolean']
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        $user->notifications = $request->input('notifications')?:0;
        $user->сomment_notifications = $request->input('сomment_notifications')?:0;
        $user->save();
        
        return redirect()->back()->with('success', 'Notifications settings updated.');               
    }
    
    public function update_privacy(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'seachable' => ['required', 'boolean'],
            'visible' => ['required', 'boolean'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        $searchName = $request->user()->active_search_names->first();
        
        $searchName->seachable = $request->input('seachable')?:0;
        $searchName->save();

        $user = $request->user();
        $user->visible = $request->input('visible')?:0;
        
        $user->save();
        
        return redirect()->back()->with('success', 'Privacy updated.');       
    }

    public function deactivate(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deactivated.');  
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $user->forceDelete();
        
        if($request->user()->isAdmin()) {
            return redirect()->route('users.index')->with('success', 'User permanently deleted');
        }
        return redirect()->route('home')->with('success', 'User permanently deleted');
    }
    
    public function delete_by_user(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'delete' => ['required', 'boolean'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        Auth::logout();
        
        $user->forceDelete();
        return redirect()->route('index')->with('success', 'User permanently deleted');  
    }
    
    public function restore(User $user) 
    {
        $user->restore();
        
        return redirect()->back()->with('success', 'User restored');  
    }
    
    public function verify(Request $request, User $user)
    {
        $type = UserType::where('class', $user->user_type->class)
                ->where('candidate', $user->user_type->candidate)
                ->where('former', $user->user_type->former)
                ->where('verified', 1)
                ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        if (!($request->user() instanceof \App\Admin)) {  
            $request->user()->verified_users()->syncWithoutDetaching([$user->id => ['created_at' => new Carbon()]]);
        }
   
        if ($user->verification_id) {
            $media = $user->verification_id->image;
            if (Storage::disk($media->disk)->exists($media->filename)) {
                Storage::disk($media->disk)->delete($media->filename);
            }

            $media->delete();
            $user->verification_id->delete();
        }
        return redirect()->back()->with('success', 'User verification updated!');  
    }
    
    public function unverify(User $user)
    {
        $type = UserType::where('class', $user->user_type->class)
            ->where('candidate', $user->user_type->candidate)
            ->where('former', $user->user_type->former)
            ->where('verified', 0)
            ->first();
        
        if (is_null($type)) {
            $type = UserType::where('class', 'member')
                ->where('candidate', $user->user_type->candidate)
                ->where('former', $user->user_type->former)
                ->where('verified', 0)
                ->first();
        }
            
        $user->user_type()->associate($type);
        $user->save();
        
        event(new UserLostVerification($user));
        
        return redirect()->back()->with('success', 'User verification updated!');  
    }
    
    public function follow(Request $request, User $user)
    {
        $user->followers()->syncWithoutDetaching($request->user());
        return redirect()->back()->with('success', 'Lead added.');  
    }
    
    public function unfollow(Request $request, User $user)
    {
        $user->followers()->detach($request->user()->id);
        return redirect()->back()->with('success', 'Lead  removed.');  
    }
    
    public function settings(User $user)
    {
        return view('users.settings')->with(compact('user'));
    }
    
    public function disqualify_membership(Request $request, User $user) 
    {
        $type = UserType::where('class', 'member')
                ->where('verified', $user->user_type->verified)
                ->where('former', 1)
                ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        event(new UserLeftIlp($user));
        
        return redirect()->back()->with('success', 'Member disqualified.');  
    }
    
    public function update_email_send_token(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'password' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ( Hash::check($request->password, $user->password) ) {
            if ($request->email !== $user->email) {
                $user->another_email = $request->email;
                $user->email_token = \Illuminate\Support\Str::random(60);

                if ($user->save()) {
                    // Fake User (send notification to new email)
                    (new User)->forceFill([
                        'name' => $user->name,
                        'email' => $user->another_email,
                        'another_email' => $user->another_email,
                        'email_token' => $user->email_token
                    ])->notify(new UserEmailChange());

                    return redirect()->back()->with('success', 'Message sent. Please check your new email inbox.');   
                }

            } else {
                return redirect()->back()->withErrors('Write new email in a box');   
            }

            return redirect()->back()->withErrors('Email update failed!');        
        }
        
        return redirect()->route('users.settings', $request->user()->id)->withErrors('Password doesn\'t match!');
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
    
    public function verification_id_image(Request $request, User $user)
    {
        return view('users.verification_id_image')->with(compact('user'));
    }
    
    public function invite(Request $request, User $user, Idea $idea)
    {        
        $data = [
            'idea_id' => $idea->id
        ];
        
        $validator = Validator::make($data,[
            'idea_id' => ['required', 'exists:ideas,id',
                function ($attribute, $value, $fail) use ($request, $user) {
                    $idea = Idea::findOrFail($value);
            
                    if ( !$request->user()->following->contains($user))
                    {
                        $fail('You not following the user.');
                    }
                    // Idea in IP                 
                    //if ( !$request->user()->liked_ideas->contains($idea))
                    //{
                    //    $fail('You don\'t have such idea in your Ideological Profile.');
                    //}
                    
                    if ( $user->liked_ideas->contains($idea))
                    {
                        $fail('The user is already supporting the idea.');
                    }
                    
                    if ($request->user()->user_notifications_new()->where('idea_id', $idea->id)->get()->contains(function ($value, $key) use ($user, $idea){
                            return $value->pivot->receiver_id == $user->id && 
                                $value->pivot->idea_id == $idea->id;
                    }))
                    {
                        $fail('Notification already exists.');
                    }
                    
                }],
        ]);

        if ($validator->fails()) {
            if($request->ajax() || $request->wantsJson()){
                return response()->json($validator->messages(), \Illuminate\Http\Response::HTTP_BAD_REQUEST);
            }
            
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $notification = new NotificationModel();
        $notification->sender()->associate($request->user());
        $notification->receiver()->associate($user);
        $notification->idea()->associate($idea);
        $notification->save();
        
        event(new UserInvitedToIdea($notification));
        
        if($request->ajax() || $request->wantsJson()){
            return response(['message'=>'Invitation sent.'], 200);
        }
        
        return redirect()->back()->with('success', 'Invitation sent.');
        
    }
    
    public function switch(Request $request, $key, $page)
    {
        if (!in_array($key, array_keys(config('egoras')))) {
            return redirect()->back()
                    ->withInput()->withErrors(['key'=>'Incorrect switch parameter.']);
        }
        
        $request->session()->put('current_egora', $key);      

        if ($page == 'index') {
            return redirect()->route('log.index')->with('success', 'Egora switched.');
        }
        
        return redirect()->route('users.ideological_profile', $request->user()->active_search_name_hash)->with('success', 'Egora switched.');
    }
    
    public function reset(User $user)
    {
        $type = UserType::where('class', $user->user_type->class)
            ->where('candidate', $user->user_type->candidate)
            ->where('former', 0)
            ->where('verified', $user->user_type->verified)
            ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        return redirect()->back()->with('success', 'Former user set to '. $type->title);  
    }
    
    public function subdivisions(Request $request)
    {
        $user = $request->user();
        
        $subdivisions=[];
        foreach( $user->subdivisions as $i=>$subdivision) {
            $subdivisions[$subdivision->pivot->order] = $subdivision;
        }
        
        return view('users.subdivisions')->with(compact('user','subdivisions'));
    }
    
    public function subdivisions_update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'subdivisions' => 'required|array|min:23|max:23',
            'subdivisions.*' => 'nullable|string|min:3|max:190'
        ]); 

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $sync_ids =[];
        foreach(array_filter($request->subdivisions) as $i=>$subdivision) 
        {
            if ($row = \App\Subdivision::where('title', $subdivision)->where('nation_id', $request->user()->nation->id)->first()) {
                $sync_ids[$row->id] = ['order'=>$i];    
            } else {
                $row = new \App\Subdivision();
                $row->title = $subdivision;
                $row->nation_id = $request->user()->nation->id;
                $row->save();
                $sync_ids[$row->id] = ['order'=>$i];
            }
        }
        
        
        $validator = Validator::make($request->all(),[
            'subdivisions' => [function ($attribute, $value, $fail) use ($sync_ids, $request) {   
                    $old=[];
                    foreach( $request->user()->subdivisions as $i=>$subdivision) {
                        $old[$subdivision->pivot->order] = $subdivision->title;
                    }

                    if ( $request->user()->campaign && $request->user()->campaign->order 
                        && $old[$request->user()->campaign->order] != $value[$request->user()->campaign->order])
                    {
                        $fail('You have an active campaign.');
                    }            
            }]
        ]); 

        if ($validator->fails()) {
            return redirect()->route('campaigns.index')
                    ->withInput()->withErrors($validator);
        }

        $request->user()->subdivisions()->sync($sync_ids);
        
        return redirect()->back()->with('success', 'Subdivisions Updated');
    }
    
    public function disqualify(Request $request, User $user)
    {
        $request->user()->disqualifying_users()->syncWithoutDetaching($user);

        return redirect()->back()->with('success', "User is disqualified.");
    }
    
    public function qualify(Request $request, User $user)
    {
        $request->user()->disqualifying_users()->detach($user->id);

        return redirect()->back()->with('success', "User is qualified.");
    }
}
