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
use App\Comment;
use App\Events\StatusAdded;
use App\Events\StatusUpdated;
use App\Events\CommentAdded;
use App\Update;
use App\Events\IdeaSupportHasChanged;
use App\Events\IdeaUnbookmarked;
use Illuminate\Support\Facades\Session;

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
        
        $model = User::with('default_lead')->withTrashed();
        
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
        
        if ($request->has('members')) {
            $model->where(function($q) use($request){
                $q->whereHas('government_id', function($q){
                    $q->where('status','submitted');
                });
            });
        }
        
        $users = $model->orderBy('created_at', 'desc')->paginate(100);
        return view('users.index')->with(compact('users', 'search'));
    }

    public function search(Request $request)
    {        
        $search_name = null;
        $nation = null;
        $officer = null;
        $officer_petitioner = null;
        $followers = null;
        $recent = false;
        $perPage = 10;
        
        $nations = Nation::get();
        
        if ($request->exists('followers')){     
            $validator = Validator::make($request->all(),[
                'search_name' => 'nullable|min:3|string|required_without_all:officer_petitioner,officer,followers',
                'nation' => 'nullable|exists:nations,title',
                'followers' => 'nullable|boolean',
                'officer' => 'nullable|boolean',
                'officer_petitioner' => 'nullable|boolean',
            ], [
                'search_name.required_without_all' => 'Please provide search criteria.'
            ]); 

            if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }

            $search_name = $request->input('search_name');
            $nation = $request->input('nation');
            $officer = $request->input('officer');
            $followers = $request->input('followers');
            $officer_petitioner = $request->input('officer_petitioner');

            $model = $request->user()->followers()->with(['user_type','nation']);
            
            $model->where(function($q) use ($request) {
                if ($request->input('search_name')) {
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
                }
                if ($request->input('nation')) {
                    $q->whereHas('nation', function($q) use ($request){
                        $q->where('title', $request->input('nation'));
                    });
                }
                
                if ($request->input('officer')) {
                    $q->whereHas('user_type', function($q) {
                        $q->where('class','officer');
                    });
                } else if ($request->input('officer_petitioner')) {
                    $q->whereHas('user_type', function($q) {
                        $q->where('class','petitioner');
                    });
                }
            });

            $perPage = 100;
            $total_users = $model->get()->sortBy('active_search_name');
            $users = $total_users->chunk($perPage)->get(LengthAwarePaginator::resolveCurrentPage() - 1); 

            $users = new LengthAwarePaginator(
                (($users && $users->isNotEmpty()) ? $users->toArray(): []), 
                $total_users->count(), 
                $perPage, 
                LengthAwarePaginator::resolveCurrentPage(), 
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
            
        } else if ($request->exists('search_name')){     
            $validator = Validator::make($request->all(),[
                'search_name' => 'nullable|min:3|string|required_without_all:officer_petitioner,officer,followers',
                'nation' => 'nullable|exists:nations,title',
                'followers' => 'nullable|boolean',
                'officer' => 'nullable|boolean',
                'officer_petitioner' => 'nullable|boolean',
            ], [
                'search_name.required_without_all' => 'Please provide search criteria.'
            ]); 

            if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }
            
            $search_name = $request->input('search_name');
            $nation = $request->input('nation');
            $officer = $request->input('officer');
            $followers = $request->input('followers');
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
                })->where('visible', 1)->orderBy('created_at', 'desc')->paginate($perPage);

            $total = 300;
            
            $users = new LengthAwarePaginator(
                $users->toArray()['data'], 
                $users->total() < $total ? $users->total() : $total, 
                $perPage, 
                LengthAwarePaginator::resolveCurrentPage(), 
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }

        return view('users.search')->with(compact('followers', 'recent', 'users', 'nations', 'search_name', 'nation', 'officer', 'officer_petitioner'));
    }
    
    public function indexApi(Request $request)
    {
        $prefix = $request->input('q');
        
        if ($prefix) {
            $result = $request->user()->following->filter(function ($item) use ($prefix) {
                return false !== stripos($item->active_search_name, $prefix);
            })->sortBy('active_search_name')->map(function ($item) {
                return ['name' => $item['active_search_name'], 'hash'=>$item['active_search_name_hash']];
            });
        } else {
            $result = $request->user()->following->sortBy('active_search_name')->map(function ($item) {
                return ['name' => $item['active_search_name'], 'hash'=>$item['active_search_name_hash']];
            });
        }
        
        return response()->json(compact('result'));
    }
    
    public function default_leads()
    {
        $users = User::whereHas('default_lead')->paginate(10);
        
        return view('users.default_leads')->with(compact('users'));
    }
    
    public function remove_default_lead(Request $request, User $user)
    {
        if ($result = \DB::table('default_leads')->where('user_id', $user->id)->delete()) {
            return redirect()->back()->with('success', 'Default Lead removed');
        }
        
        return redirect()->back()->withErrors('Can\'t remove default lead.');
    }    
    
    public function add_default_lead(Request $request, User $user)
    {
        if ($result = \DB::table('default_leads')->insertOrIgnore(['user_id' => $user->id])) {
            return redirect()->back()->with('success', 'Default Lead added');
        }
        
        return redirect()->back()->withErrors('Can\'t default lead.');
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

        $leads = $user->following()->where('visible',1)->with('nation', 'search_names')->get()->sortBy('active_search_name');
        
        return view('users.leadsbyid')->with(compact('leads', 'user', 'community_id'));
    }
    
    public function followersbyid(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        // Change;
        if ($request->user()->isAdmin()) {
            $community_id = $user->communities->first()->id;
        } else {
            $community_id = ($request->has('community_id')?$request->community_id :  $request->user()->communities->first()->id);
        }

        $total = $user->followers->count();
        
        $model = $user->followers()->with(['user_type','nation'])
                ->where('visible',1)
                ->join('search_names', 'followers.follower_id', '=', 'search_names.user_id')
                ->orderBy('search_names.name');
                
        $followers = $model->paginate(100); 
        
        return view('users.followersbyid')->with(compact('followers', 'user', 'community_id', 'total'));
    }
    
    public function communities(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $communities=[];
        
        $dec = $user->communities_not_allowed_to_leave->count();
        
        $i=0;
        foreach( $user->communities_allowed_to_leave->sortBy('pivot.order') as $community) {
            $communities[$i++] = $community;
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
            'municipality' => ['required', 'string', 'max:92'],
        ],[
            'municipality.max' => 'Municipality may not be greater than :max characters.'
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
            'communities' => 'required|array|min:23|max:23',
            'communities.*' => 'nullable|string|min:3|max:92'
        ],[
            'communities.*.max' => 'A community may not be greater than :max characters.'
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
    
    public function _ideological_profile($view, Request $request, $search_name)
    {
        $searchname = SearchName::with('user')->where('name', _url_search_name($search_name))->first();
        $user = $searchname->user;
            
        $ownIP = false;
        $_ideas = collect();
        
        // Change;
            $community_id = ($request->has('community_id')?$request->community_id :  $user->communities->first()->id);
            
            if ($request->user()) {
                $_user = $request->user();
                $_user->load(['liked_ideas' => function($q) use ($community_id, $user, $request) {
                    if (is_egora('community') && $community_id) {
                        $q->where('ideas.community_id', $community_id);
                    } else {
                        $q->whereNull('ideas.community_id');
                    }

                    if (is_egora('municipal')) {
                        $q->where('ideas.municipality_id', $user->municipality_id);
                        $q->whereNotNull('ideas.municipality_id');
                    } else {
                        $q->whereNull('ideas.municipality_id');
                    }

                    //don't include E,G,O,R,A ideas 
                    //$q->where('idea_user.order', '>=', 0 );
                }]);
                
                $_ideas = $_user->liked_ideas->pluck('id');
                $ownIP = $user->id == $_user->id;
            }
        
        $user->load(['liked_ideas' => function($q) use ($community_id, $user, $request) {
            $q->with('comments.comments');
            if (is_egora('community') && $community_id) {
                    $q->where('ideas.community_id', $community_id);
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
        }, 'communities' => function($q) use ($request, $user) {
            $q->whereIn('id', $user->communities->pluck('id'));
        }]);

        $shared_ideas = null;
        $ideas=collect();
        $ip_score = 0;
        $scores = [];        
        foreach($user->liked_ideas as $idea) {
            if ($idea->pivot->order >= 0) {
                $scores[] = $idea->liked_users->pluck('pivot.position')->sum();
                $ideas->push($idea->id);
            }
        }        
        rsort($scores, SORT_NUMERIC);
        $ip_score = array_sum(array_slice($scores, 0, 23));

        if (!$ownIP && isset($_user)) {
            $filtered = $_user->liked_ideas->filter(function($v,$k){
                return $v->pivot->order >= 0;
            });
            
            $shared_ideas = $ideas->intersect($filtered->pluck('id'));
        }
        
        return view($view)->with(compact('user', 'community_id', 'ip_score', '_ideas', 'ideas', 'ownIP', 'shared_ideas'));
    }
    
    public function municipal_vote_ideological_profile(Request $request, $search_name)
    {
        switch_to_egora('municipal');
        return $this->_ideological_profile('users.ideological_profile', $request, $search_name)->with(array('vote_ip' => 1));
    }
    public function community_vote_ideological_profile(Request $request, $search_name)
    {
        switch_to_egora('community');
        return $this->_ideological_profile('users.ideological_profile', $request, $search_name)->with(array('vote_ip' => 1));
    }
    
    public function vote_about(Request $request, $search_name)
    {
        $searchname = SearchName::with('user')->where('name', _url_search_name($search_name))->first();
        $user = $searchname->user;
        $hash = $searchname->hash;
        
        $user->load(['petition.supporters' => function($q) {
            $q->recent();
        }]);
        
        $comments = $user->comments()->orderBy('created_at', 'desc')->paginate(25);
        
        $open = ($request->has('open') ? (int) $request->input('open') : null);
        
        return view('users.about')->with(compact('user','hash', 'comments', 'open'))->with(array('vote'=>1));
    }    
    
    public function vote_ideological_profile(Request $request, $search_name)
    {
        switch_to_egora();
        return $this->_ideological_profile('users.ideological_profile', $request, $search_name)->with(array('vote_ip' => 1));
    }
    
    public function external_ideological_profile(Request $request, $search_name)
    {
        return $this->_ideological_profile('users.ideological_profile', $request, $search_name)->with(array('external_ip' => 1));
    }
    
    public function ideological_profile(Request $request, $hash)
    {
        if ($request->input('switch')) {
            switch_to_egora($request->input('switch'));
        }
        
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $validator = Validator::make($request->all(),[
            'pdf' => ['boolean', 
                function ($attribute, $value, $fail) use ($request, $user) {                    
                    if ($request->user()->id != $user->id)
                    {
                        $fail('You are not allowed to download other users pdf.');
                    }
                }],
            'community_id' => ['numeric', function ($attribute, $value, $fail) use ($request, $user) {                    
                    if ($request->user() && !$request->user()->isAdmin()) {
                        if (!$request->user()->communities->contains($request->community_id ))
                        {
                            $fail('You are not allowed to list the community.');
                        }
                    }
                }]
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $ownIP = false;
        $_ideas = collect();
        
        // Change;
        if ($request->user()->isAdmin()) {
            $community_id = $user->communities->first()->id;
        } else {
            $community_id = ($request->has('community_id')?$request->community_id :  $request->user()->communities->first()->id);
            
            if ($request->user()) {
                $_user = $request->user();
                $_user->load(['liked_ideas' => function($q) use ($community_id, $user, $request) {
                    if (is_egora('community') && $community_id) {
                        $q->where('ideas.community_id', $community_id);
                    } else {
                        $q->whereNull('ideas.community_id');
                    }

                    if (is_egora('municipal')) {
                        $q->where('ideas.municipality_id', $user->municipality_id);
                        $q->whereNotNull('ideas.municipality_id');
                    } else {
                        $q->whereNull('ideas.municipality_id');
                    }

                    //don't include E,G,O,R,A ideas 
                    //$q->where('idea_user.order', '>=', 0 );
                }]);
                
                $_ideas = $_user->liked_ideas->pluck('id');
                $ownIP = $user->id == $_user->id;
            }
        }
        
        $user->load(['liked_ideas' => function($q) use ($community_id, $user, $request) {
            $q->with('comments.comments');
            if (is_egora('community') && $community_id) {
//                if ($request->has('pdf')) {
//                    $q->whereNotNull('ideas.community_id');                    
//                } else {
                    $q->where('ideas.community_id', $community_id);
//                }
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
            if (!$request->user()->isAdmin()) {
                $q->whereIn('id', $request->user()->communities->pluck('id'));
            }
        }]);

        $shared_ideas = null;
        $ideas=collect();
        $ip_score = 0;
        $scores = [];        
        foreach($user->liked_ideas as $idea) {
            if ($idea->pivot->order >= 0) {
                $scores[] = $idea->liked_users->pluck('pivot.position')->sum();
                $ideas->push($idea->id);
            }
        }        
        rsort($scores, SORT_NUMERIC);
        $ip_score = array_sum(array_slice($scores, 0, 23));

        if (!$ownIP) {
            $filtered = $_user->liked_ideas->filter(function($v,$k){
                return $v->pivot->order >= 0;
            });
            
            $shared_ideas = $ideas->intersect($filtered->pluck('id'));
        }
        
        if ($request->has('pdf')) {
            if (is_egora('community')) {
                $liked_ideas = collect();
                foreach($user->communities as $community) {
                    foreach($user->liked_ideas->where('community_id', $community->id) as $idea) {
                        $liked_ideas->push($idea);
                    }
                }
            } else {
                $liked_ideas = $user->liked_ideas;
            }
                
            view()->share(compact('user', 'community_id', 'ip_score', 'liked_ideas'));
            $pdf_doc = \PDF::loadView('users.ideological_profile_pdf');

//            return $pdf_doc->stream(date('U').'.pdf', array('Attachment'=>0));
            return $pdf_doc->download(date('U').'.pdf');
        }
        
        return view('users.ideological_profile')->with(compact('user', 'community_id', 'ip_score', '_ideas', 'ideas', 'ownIP', 'shared_ideas'));
    }
    
    public function bookmarked_ideas(Request $request)
    {
        $user = $request->user();

        $_ideas = collect();
        
        $community_id = ($request->has('community_id')?$request->community_id :  $request->user()->communities->first()->id);
        
        
        $user->load(['bookmarked_ideas' => function($q) use ($community_id, $user, $request) {
            $q->with('comments.comments');
            if (is_egora('community') && $community_id) {
//                if ($request->has('pdf')) {
//                    $q->whereNotNull('ideas.community_id');                    
//                } else {
                    $q->where('ideas.community_id', $community_id);
//                }
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
            if (!$request->user()->isAdmin()) {
                $q->whereIn('id', $request->user()->communities->pluck('id'));
            }
        }]);
        
        
        return view('users.bookmarked_ideas')->with(compact('user', 'community_id'));
    }
    
    public function external_about(Request $request, $search_name)
    {
        $searchname = SearchName::with('user')->where('name', _url_search_name($search_name))->first();
        $user = $searchname->user;
        $hash = $searchname->hash;
        
        $user->load(['petition.supporters' => function($q) {
            $q->recent();
        }]);
        
        $comments = $user->comments()->orderBy('created_at', 'desc')->paginate(25);
        
        $open = ($request->has('open') ? (int) $request->input('open') : null);
        
        return view('users.about')->with(compact('user','hash', 'comments', 'open'))->with(array('external'=>1));
    }
    
    public function about(Request $request, $hash)
    {
        $searchname = SearchName::where('hash', $hash)->get()->first();
        $user = $searchname->user;
        
        $user->load(['petition.supporters' => function($q) {
            $q->recent();
        }]);
        
        $comments = $user->comments()->orderBy('created_at', 'desc')->paginate(25);
        
        $open = ($request->has('open') ? (int) $request->input('open') : null);
        
        return view('users.about')->with(compact('user','hash', 'comments', 'open'));
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
        $_ideas = collect();
        $ideas = [];
        
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

        return view('users.ideological_profile')->with(compact('user', '_ideas', 'ideas'));
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
        $office_hours = $user->office_hours ?: [];
        return view('users.edit')->with(compact('user','searchName', 'office_hours'));        
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
            'name' => ['required', 'string', 'regex:/^[a-z\d ]+$/i', 'max:92', 
                function ($attribute, $value, $fail) use ($request, $user) {                    
                    if ($request->user()->id == $user->id && !$user->user_type->isOfficer 
                            && !is_null($user->campaign) && $user->name !== $value )
                    {
                        $fail('As a candidate, you are not allowed to change '.$attribute.' currently.');
                    }
                }],
            'national_affiliations' => ['nullable', 'string', 'regex:/^[a-z\d ]+$/i', 'max:92'],
            'current_password' => ['required', 'password'],
            'search_name' => 'required|min:3|max:92|string|unique:search_names,name,'.$searchName->id.'|regex:/^[a-z\d ]+$/i',                        
            'delete_followers' => ['boolean', 'nullable',
                function ($attribute, $value, $fail) use ($request, $searchName) {
                    if ($request->search_name == $searchName->name)
                    {
                        $fail('Please change your Search-Name.');
                    }
                }],
            'email_address' => ['nullable', 'string', 'email', 'max:92'],
            'phone_number' => ['nullable', 'string', 'max:92'],
            'social_media_1' => ['nullable', 'string', 'max:92'],
            'social_media_2' => ['nullable', 'string', 'max:92'],
            'messenger_1' => ['nullable', 'string', 'max:92'],
            'messenger_2' => ['nullable', 'string', 'max:92'],                        
            'other_1' => ['nullable', 'string', 'max:230'],
            'other_2' => ['nullable', 'string', 'max:230'],
            'nation' => ['required', 'string', 'regex:/^[a-z\d ]+$/i', 'max:92',
                function ($attribute, $value, $fail) use ($request, $user) {
                    if ($request->user()->id == $user->id && $user->nation->title !== $value && ($user->user_type->isOfficer || $user->user_type->isPetitioner || !is_null($user->campaign)))
                    {
                        $fail('You are not allowed to change '.$attribute.' currently.');
                    }
                }],
            'office_hours' => [function ($attribute, $value, $fail) use ($request) {
                    $office_hours = filter_office_hours_array($request->office_hours);
                    
                    if ( !empty($office_hours) && empty($request->time_zone))
                    {
                        $fail('Time Zone can\'t be empty');
                    }
                }
            ],
            'office_hours.*.day' => ['required_with:office_hours.*.from,office_hours.*.to'],
            'office_hours.*.from' => ['required_with:office_hours.*.day,office_hours.*.to',
                function ($attribute, $value, $fail) use ($request) {
                    $i = (int) str_replace(['office_hours.', '.from', '.to'], '', $attribute);
                    if ($value && (new Carbon())->parse($value)->greaterThan((new Carbon())->parse($request->input('office_hours.'.$i.'.to'))) )
                    {
                        $fail('The '.$attribute.' must be less than '.'office_hours.'.$i.'.to'.' time.');
                    }
                }
            ],
            'office_hours.*.to' => ['required_with:office_hours.*.day,office_hours.*.from', 
                function ($attribute, $value, $fail) use ($request) {
                    $i = (int) str_replace(['office_hours.', '.from', '.to'], '', $attribute);
                    if ($value && (new Carbon())->parse($value)->lessThan((new Carbon())->parse($request->input('office_hours.'.$i.'.from'))) )
                    {
                        $fail('The '.$attribute.' must be greater than '.'office_hours.'.$i.'.from'.' time.');
                    }
                }
            ],
            'time_zone' => ['nullable', 'string', 'max:46', function ($attribute, $value, $fail) use ($request) {
                    $office_hours = filter_office_hours_array($request->office_hours);
                    
                    if ( empty($office_hours) )
                    {
                        $fail('Please fill Office Hours.');
                    }
                }],
            'meeting_location' => ['nullable', 'string', 'max:92', function ($attribute, $value, $fail) use ($request) {
                    $office_hours = filter_office_hours_array($request->office_hours);
                    
                    if ( empty($office_hours) )
                    {
                        $fail('Please fill Office Hours.');
                    }
                }],
            'calendar_link' => ['nullable', 'string', 'max:92', function ($attribute, $value, $fail) use ($request) {
                    $office_hours = filter_office_hours_array($request->office_hours);
                    
                    if ( empty($office_hours) )
                    {
                        $fail('Please fill Office Hours.');
                    }
                }],
        ],[
//            'office_hours.*.from.required_with' => 'Office Hours From required if Day selected',
//            'office_hours.*.to.required_with' => 'Office Hours To required if Day selected',
            'time_zone.max' => 'Time Zone may not be greater than :max characters.',     
            'meeting_location.max' => 'Meeting Location / Link to Videoconference may not be greater than :max characters.',     
            'calendar_link.max' => 'Link to Scheduling Calendar may not be greater than :max characters.',     
            'name.max' => "Your Name may not be greater than :max characters.",
            'search_name.max' => "Search-Name may not be greater than :max characters.",
            'nation.max' => "Nation may not be greater than :max characters.",
            'national_affiliations.max' => "Other National Affiliations may not be greater than :max characters.",
            'email_address.max' => "Email Address may not be greater than :max characters.",
            'phone_number.max' => "Phone Number may not be greater than :max characters.",
            'social_media_1.max' => "Social Media 1 may not be greater than :max characters.",
            'social_media_2.max' => "Social Media 2 may not be greater than :max characters.",
            'messenger_1.max' => "Messenger 1 may not be greater than :max characters.",
            'messenger_2.max' => "Messenger 2 may not be greater than :max characters.",
            'other_1.max' => "Other 1 may not be greater than :max characters.",
            'other_2.max' => "Other 2 may not be greater than :max characters.",
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ($searchName->name != $request->input('search_name')) {
            $searchName->name = $request->input('search_name');
            $searchName->hash = base64_encode(Hash::make($request->input('search_name')));
            $searchName->save();
            event(new SearchNameChanged($searchName->user, $request));
        }
        
        if ($user->name != $request->name) {
            $user->name = $request->name;
            event(new UserNameChanged($user));
        }        
        $user->national_affiliations = $request->national_affiliations;
        $user->email_address = $request->email_address;
        $user->phone_number = $request->phone_number;
        $user->social_media_1 = $request->social_media_1;
        $user->social_media_2 = $request->social_media_2;
        $user->messenger_1 = $request->messenger_1;
        $user->messenger_2 = $request->messenger_2;
        $user->other_1 = $request->other_1;
        $user->other_2 = $request->other_2;
        
        $user->office_hours = $request->office_hours;
        $user->meeting_location = trim($request->meeting_location);
        $user->calendar_link = trim($request->calendar_link);
        $user->time_zone = trim($request->time_zone);

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
            return redirect()->route('users.ideological_profile', $request->user()->active_search_names->first()->hash)->with('success', 'Information updated!');   
        }
        
        return redirect()->route('users.ideological_profile', $request->user()->active_search_names->first()->hash)->withErrors('User information update failed!');   
    }

    public function clear_coh(Request $request)
    {
        $user = $request->user();
        
        if (isset($request->row) && isset($user->office_hours[$request->row])) {
            $office_hours = $user->office_hours;
            $office_hours[$request->row] = ["day"=>null,"from"=>null,"to"=>null];
            $user->office_hours = $office_hours;
            $user->save();
        }
        
        return redirect()->route('users.edit', [$user->id, "#coh"])->with('success', 'Cleared.');         
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
            'external_visible' => ['required', 'boolean'],
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
        $user->external_visible = $request->input('external_visible')?:0;
        
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
        $ideas = $user->liked_ideas;
        
        $user->approval_ratings()->delete();
        
        $user->forceDelete();
        
        $ideas->each(function($idea, $key) {
            event(new IdeaSupportHasChanged(new User(), $idea));
        });

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
        
        $ideas = $user->liked_ideas;
        
        $user->approval_ratings()->delete();
        
        Auth::logout();
        
        $user->forceDelete();
        
        $ideas->each(function($idea, $key) {
            event(new IdeaSupportHasChanged(new User(), $idea));
        });
        
        return redirect()->route('index')->with('success', 'User permanently deleted');  
    }
    
    public function restore(User $user) 
    {
        $user->restore();
        
        return redirect()->back()->with('success', 'User restored');  
    }
    
    private function _government_id_delete(Request $request)
    {
        $user = $request->user();
        
        if ($user->government_id) {
            $media = $user->government_id->image;
            if (Storage::disk($media->disk)->exists($media->filename)) {
                Storage::disk($media->disk)->delete($media->filename);
            }

            $media->delete();
            $user->government_id->delete();
        }
    }
    
    public function government_id_delete(Request $request)
    {
        $this->_government_id_delete($request);
        
        return redirect()->back()->with('success', 'Your Government ID is deleted.');  
    }
    
    public function government_id_reupload(Request $request)
    {
        $this->_government_id_delete($request);
        
        return redirect()->route('ilp.index');
    }

    public function accept_id(Request $request, User $user)
    {
        $type = UserType::where('class', 'member')
            ->where('verified', $user->user_type->verified)
            ->where('candidate', 0) //Accepr member ILP declaration
            ->first();

        $user->user_type()->associate($type);
        $user->save();
        
        if ($user->government_id) {
            $media = $user->government_id->image;
            if (Storage::disk($media->disk)->exists($media->filename)) {
                Storage::disk($media->disk)->delete($media->filename);
            }

            $media->delete();
            $user->government_id->delete();
        }
        return redirect()->back()->with('success', 'Accepted the ID.');  
    }
    
    public function reject_id(Request $request, User $user)
    {
        $user->government_id->status = 'rejected';
        $user->government_id->save();
        return redirect()->back()->with('success', 'Rejected the ID.');  
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
        return redirect()->back()->with('success', 'Verification status updated.');  
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
        
        return redirect()->back()->with('success', 'Verification status updated.');  
    }
    
    public function follow(Request $request, User $user)
    {
        $user->followers()->syncWithoutDetaching($request->user());
        
//        if ($user->updates->count() < 99) {
        if(!$user->inactive){
            $update = new Update();
            $update->user_id = $user->id;
            $update->egora_id = config('egoras.default.id');
            $update->type = 'follower';
            $request->user()->update_relation()->save($update);
        }
        
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
    
    public function government_id_image(Request $request, User $user)
    {
        return view('users.government_id_image')->with(compact('user'));
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
        
        return redirect()->back()->with('success', 'Member restored.');  
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
            'subdivisions.*' => 'nullable|string|min:3|max:92'
        ],[
            'subdivisions.*.max' => 'A subdivision may not be greater than :max characters.'
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

        return redirect()->back()->with('success', "Disqualified.");
    }
    
    public function qualify(Request $request, User $user)
    {
        $request->user()->disqualifying_users()->detach($user->id);

        return redirect()->back()->with('success', "Qualified.");
    }
    
    public function status(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'message' => 'required|min:3|max:2300|string',
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $comment = $user->addComment($request->input('message'), $request->user()->id);
        
        event(new StatusAdded($comment));
        
        return redirect()->to(route('users.about', $user->active_search_names->first()->hash).'#comment-'.$comment->id)->with('success', 'Status added.'); 
    }
    
    public function status_reply(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(),[
            'message' => 'required|min:3|max:2300|string',
        ]);
         
        if ($validator->fails()) {  
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $new = $comment->addComment($request->input('message'), $request->user()->id);
                
        event(new CommentAdded($new));
         
        return redirect()->to(route('users.about', [$comment->commentable->active_search_names->first()->hash, 'open'=>$comment->id]).'#comment-'.$new->id)->with('success', 'Reply added.'); 
        
    }
    
    public function status_update(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(),[
            'message' => 'required|min:3|max:2300|string',
        ]);
         
        if ($validator->fails()) {  
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        $old_message = $comment->message;
        $comment->message = $request->input('message');
        $comment->save();

        event(new StatusUpdated($comment, $old_message));

        return redirect()->to(route('users.about', [$comment->commentable->active_search_names->first()->hash]).'#comment-'.$comment->id)->with('success', 'Status updated.'); 
    }    
    
    public function update_role(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'role' => 'required|max:92|string',
        ]);
         
        if ($validator->fails()) {  
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $user->role = $request->input('role');
        $user->save();
        
        return redirect()->back()->with('success', 'Role updated.'); 
    }
    
    public function remove_role(Request $request, User $user)
    {
        $user->role = null;
        $user->save();
        
        return redirect()->back()->with('success', 'Role removed.'); 
    }
    
    public function clear_account(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'current_password' => ['required', 'password'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }        
        
        $user = $request->user();
        
        $liked_ideas = $user->liked_ideas;
        $user->liked_ideas()->sync([]);

        $liked_ideas->each(function($idea, $key) use ($user) {
            event(new IdeaSupportHasChanged($user, $idea));
        });        
        
        $bookmarked_ideas = $user->bookmarked_ideas;
        $user->bookmarked_ideas()->sync([]);

        $bookmarked_ideas->each(function($idea, $key) use ($user) {
            event(new IdeaUnbookmarked($user, $idea));
        });        
        
        $user->approval_ratings()->delete();
        
        $user->followers()->sync([]);
        $user->following()->sync([]);
        
        \App\Comment::whereHas('user', function($q) use ($user){
            $q->where('id', $user->id);
        })->forceDelete();
        
        \App\Update::whereHas('user', function($q) use ($user) {
            $q->where('id', $user->id);
        })->forceDelete();
        
        \App\LogLine::whereHas('user', function($q) use ($user) {
            $q->where('id', $user->id);
        })->forceDelete();

        \App\BookmarkNotification::whereHas('sender', function($q) use ($user){
            $q->where('id', $user->id);
        })->forceDelete();
        \App\BookmarkNotification::whereHas('receiver', function($q) use ($user){
            $q->where('id', $user->id);
        })->forceDelete();
        
        \App\CommentNotification::whereHas('sender', function($q) use ($user){
            $q->where('id', $user->id);
        })->forceDelete();
        \App\CommentNotification::whereHas('receiver', function($q) use ($user){
            $q->where('id', $user->id);
        })->forceDelete();
        
        \App\Notification::whereHas('sender', function($q) use ($user){
            $q->where('id', $user->id);
        })->forceDelete();
        \App\Notification::whereHas('receiver', function($q) use ($user){
            $q->where('id', $user->id);
        })->forceDelete();
        
        \App\Update::whereHasMorph('updatable', [User::class], function($q) use ($user) {
            $q->where('id', $user->id);
        })->delete();
        
        $user->petition()->forceDelete();
        $user->meetings()->delete();
        
        $user->national_affiliations = null;
        $user->phone_number = null;
        $user->social_media_1 = null;
        $user->social_media_2 = null;
        $user->messenger_1 = null;
        $user->messenger_2 = null;
        $user->other_1 = null;
        $user->other_2 = null;
        
        $user->office_hours = null;
        $user->meeting_location = null;
        $user->calendar_link = null;
        $user->time_zone = null;
        
        $user->about_me = null;
        
        $user->notifications = 0;
        $user->сomment_notifications = 0;
        $user->visible = 0;
        $user->external_visible = 0;
        $user->disappeared = 1;
        
        $user->save();
        
        if ($user->campaign) {
            $user->campaign->delete();
        }
        
        $searchName = $user->active_search_names->first();
        $searchName->active = 0;
        $searchName->seachable = 0;
        $searchName->save();
        
        Auth::logout();
        Session::flush();
        Session::regenerate();
//        $user->delete();
        
        return redirect()->route('index')->with('success', 'You have vanished.');  
    }
}
