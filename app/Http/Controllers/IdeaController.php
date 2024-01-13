<?php

namespace App\Http\Controllers;

use App\Idea;
use App\Nation;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\IdeaSupportHasChanged;
use Illuminate\Validation\Rule;
use App\NotificationPreset;
use App\Notification as NotificationModel;
use App\Events\UserLikedIdeaFromNotification;
use App\Events\CommentAdded;
use App\CommentNotification;
use App\Events\UserIdeologicalProfileChanged;
use App\Events\UserIdeologicalProfileIdeaMoved;
use App\Events\IdeaUnbookmarked;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Idea::latest()->paginate(100);
        
        return view('ideas.index')->with(compact('ideas'));
    }
    
    public function _ideas(Request $request, $view, $limit = false) 
    {
        $ideas = collect();
        $search = null;
        $relevance = null;
        $community = null;
        $municipality = null;
        $nation = null;
        $unverified = null;
        $nations = $this->_nation_select($request, $view);
        $all_nations = Nation::get();
        
        // if creator is not deactivated
        $model = Idea::query();
        if (collect($request->all())->except(['index', 'sort', 'source_id'])->isNotEmpty())
        {
            if (is_egora()) {
                $validator = Validator::make($request->all(),[
                    'search' => 'nullable|min:3|string',
                    'relevance' => ['nullable', 'numeric', 'required_without:nation'],
                    'nation' => 'nullable|exists:nations,title|required_without:relevance',
                    'unverified' => 'nullable|boolean',
                ]);
            } else if (is_egora('community')) {
                $validator = Validator::make($request->all(),[
                    'search' => 'nullable|min:3|string',
                    'community' => ['nullable', 'numeric', 'exists:communities,id'],
                    'unverified' => 'nullable|boolean',
                ]);
            } else if (is_egora('municipal')) {
                $validator = Validator::make($request->all(),[
                    'search' => 'nullable|min:3|string',
                    'relevance' => ['nullable', 'numeric', 'in:-1,'.$request->user()->municipality->id ],
                    'municipality' => 'nullable|exists:municipalities,title',
                ]);
            }
            
            if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }

            // Note: 'required_without:nation' 
            if ($request->has('relevance') && $request->input('relevance')==0 && empty($request->input('nation'))) {
                return redirect()->back()
                        ->withInput()->withErrors('Please check relevance.');                
            }
            
            $unverified = $request->input('unverified');
            // NOTE: Show all users ideas
//            $model->whereHas('user.user_type',function($q){
//                $q->where('verified', 1);
//            });
            
            $search = $request->input('search');
            $relevance = $request->input('relevance');
            $nation = $request->input('nation');
            $community = $request->input('community');
            $municipality = $request->input('municipality');
            
        } else {
            if (is_egora()) {
                $relevance = -1;
            }
        }
            
            $model->where(function($q) use ($request, &$community, $municipality, $search, &$relevance, $nation){
                if($search) {
                    $q->where('content','like', '%'.$search.'%');
                }
                
                if($request->has('source_id')) {
                    $q->where('idea_id', (int) $request->input('source_id'));
                }
                
                if (is_egora()) {
                    $q->where(function($q) use ($relevance, $nation){
                        if($relevance && $relevance != -1 && $relevance != -2) {
                            $q->where('nation_id', $relevance);
                        } else if ($relevance && $relevance == -1) {
                            $egora = Nation::where('title', 'Egora')->first();
                            $q->where('nation_id', '<>', $egora->id);
                        } else if ($relevance && $relevance == -2) {
                            $q->where('nation_id', '<>', null);
                        }

                        if($nation) {
                            $q->orWhereHas('nation', function($q) use ($nation) {
                                $q->where('title', 'like', $nation.'%');
                            });
                        }
                    });
                } elseif (is_egora('community')) {
                    $q->where(function($q) use (&$community, $request){
                        if($community && $community != 0) {
                            $q->where('community_id', $community);
                        } else {
                            if ($request->user() && !$request->user()->communities->pluck('id')->isEmpty()) {
                                $community = $request->user()->communities->pluck('id')->first();
                                $q->where('community_id', $community);                
                            }
                        }
                    });
                } elseif (is_egora('municipal')) {
                    $q->where(function($q) use (&$relevance, $municipality, $request){
                        if($relevance != -1 && $request->user()) {
                            $relevance = $request->user()->municipality->id;
                            $q->where('municipality_id', $relevance);                
                        }
                        
                        if($municipality) {
                            $q->orWhereHas('municipality', function($q) use ($municipality) {
                                $q->where('title', 'like', $municipality.'%');
                            });
                        }
                    });
                    
                }
                
                $q->where('egora_id', current_egora_id());                
            });
            
            $model->whereHas('liked_users', function($q) use ($request) {
//                $q->recent();
                
                if (!$request->input('unverified')) {
                    $q->whereHas('user_type', function($q){
                        $q->verified();                
                    });
                }
            });
            
            $model->withCount(['liked_users' => function($q) use ($request){
                if (!$request->input('unverified')) {
                    $q->whereHas('user_type', function($q){
                        $q->where('verified', 1);                
                    });
                }
                $q->recent();                
                
                if (!is_egora()) {
                    $q->where('idea_user.order', '>=', 0 );
                }
            }, 'moderators' => function($q) use ($request){
                $q->recent();
                if (!$request->input('unverified')) {
                    $q->whereHas('user_type', function($q){
                        $q->where('verified', 1);                
                    });
                }
            }]);
            
            
            $subSql = 'select sum(`idea_user`.`position`) from `users` inner join `idea_user` on `users`.`id` = `idea_user`.`user_id` '
                    . 'where `ideas`.`id` = `idea_user`.`idea_id` and exists (select * from `user_types` where `users`.`user_type_id` = `user_types`.`id`';

            if (!$request->input('unverified')) {
                    $subSql .= ' and `verified` = 1';
            }

            $subSql .= ') and `users`.`deleted_at` is null';
            $subSql .= ' and DATEDIFF(now(), `users`.`last_online_at`) < 23';

            $model->selectSub($subSql, 'liked_users_sum');
            
            
            if ($request->has('sort') && (!$request->has('index'))) {
                $model->orderBy('created_at','desc');
            } else if ($view == 'ideas.popularity_indexes' || $request->input('index') == 'popularity'){
                if (!is_egora()) {
                    $model->orderBy(\DB::raw('(`liked_users_count` - `moderators_count`)'), 'desc');
                } else {
                    $model->orderBy('liked_users_count', 'desc');
                }
                $model->orderBy('liked_users_sum', 'desc');
            } else if ($request->input('index') == 'dominance') {
                $model->orderBy('liked_users_sum', 'desc');
                $model->orderBy('liked_users_count', 'desc');
            } else {
                $model->orderBy('liked_users_sum', 'desc');
                $model->orderBy('liked_users_count', 'desc');
            }

            $model->with(['comments.comments', 'nation','community', 'liked_users' => function($q) use ($request) {
                if (!$request->input('unverified')) {
                    $q->whereHas('user_type', function($q){
                        $q->where('verified', 1);                
                    });
                }
                $q->recent();
            }]);
            
            if ($limit) {
                $ideas = new \Illuminate\Pagination\Paginator($model->take($limit)->get(), 100);
            } else {
                $ideas = $model->paginate(100);
            }

//        } else {
//            if (is_egora() && isset($request->user()->nation)) {
//                $relevance = $request->user()->nation->id;
//            }
//        }
                    
        $user = $request->user();
        $sort = $request->input('sort');
        $index = $request->input('index');
        
        return view($view)->with(compact('index','sort', 'user', 'ideas', 'nations', 'all_nations', 'search', 'relevance', 'unverified', 'nation', 'community', 'municipality'));
    }
    
    public function welcome(Request $request)
    {
        $index = $request->has('index')? $request->input('index'): 'dominance';
        
        return $this->_ideas($request, 'welcome', 46)->with(compact('index'));
    }
    
    public function indexes(Request $request)
    {
        return $this->_ideas($request, 'ideas.indexes');
    }
    
    public function popularity_indexes(Request $request)
    {
        return $this->_ideas($request, 'ideas.popularity_indexes');
    }
    
    public function ipi(Request $request)
    {
        $ideas =  Idea::whereHas('user', function($q) use ($request){
            $q->where('id', $request->user()->id);
        })->get();
        
        return view('ideas.index')->with(compact('ideas'));
    }

    public function search(Request $request) 
    {
        $validator = Validator::make($request->all(),[
            'search' => 'required|min:3|string',
        ]);
         
        if ($validator->fails()) {
            return redirect()->route('ideas.index')
                    ->withInput()->withErrors($validator);
        }
        
        $search = $request->input('search');
        $ideas = Idea::where('content','like', '%'.$search.'%')->paginate(100);
        
        return view('ideas.index')->with(compact('ideas'));
    }
    
    private function _user_nation(Request $request)
    {
        $sorted = collect();
        $sorted->push( Nation::where('title', 'Universal')->first() );
        $sorted->push($request->user()->nation);
        
        if ($request->user()->user_type->class !== 'user') {
            $sorted->push( Nation::where('title', 'Egora')->first() );
        }

        return $sorted;
    }
    
    private function _nation_select(Request $request, $view)
    {
        $result = [
            'All Categories, except Egora'=>-1, 
            'All Categories'=>-2, 
        ];
        
        $nation = Nation::where('title', 'Universal')->first();
        if ($nation) {
            $result[$nation->title] = $nation->id;
        }
        
        if (isset($request->user()->nation)) {
            $result[$request->user()->nation->title] = $request->user()->nation->id;
        }
        $result['-'] = 0;
        
        if ($view == 'ideas.popularity_indexes') {
            $nation = Nation::where('title', 'Egora')->first();
            if ($nation) {
                $result[$nation->title] = $nation->id;
            }
        }
        
        return $result;
    }
    
    private function _numbers_zeros(Request $request, $ideas, $idea=null)
    {
        return ip_used_places($ideas, $idea);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
        $nations = $this->_user_nation($request);
        $community_id = null;
        $ideas = [];

        if(is_egora()) {
            $ideas = $request->user()->liked_ideas->whereNotNull('nation_id');
        } else if(is_egora('community')) {
            $community_id = $request->community_id;
            $ideas = $request->user()->liked_ideas->where('community_id', $request->community_id);
        } else if(is_egora('municipal') && !is_null($request->user()->municipality_id)) {
            $ideas = $request->user()->liked_ideas->whereNotNull('municipality_id');
        }
        list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $ideas);
        
        $user = $request->user();
        
        return view('ideas.create')->with(compact('community_id', 'user', 'nations', 'numbered', 'current_idea_position'));
    }
    
    public function copy(Request $request, Idea $idea)
    {        
        $nations = $this->_user_nation($request);
        $community_id = null;
        $nation_id = null;
        $ideas=[];
        $copy=1;
        
        if(is_egora()) {
            $nation_id = $idea->nation ? $idea->nation->id : null ;
            $ideas = $request->user()->liked_ideas->whereNotNull('nation_id');
        } else if(is_egora('community')) {
            $community_id = $request->community_id ?: ($idea->community ? $idea->community->id : null);
            $ideas = $request->user()->liked_ideas->where('community_id', $community_id);
        } else if(is_egora('municipal') && !is_null($request->user()->municipality_id)) {
            $ideas = $request->user()->liked_ideas->whereNotNull('municipality_id');
        }
        list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $ideas);
        
        $text = $idea->content;

        $user = $request->user();

        return view('ideas.create')->with(compact('copy', 'idea', 'nation_id', 'community_id', 'user', 'nations', 'numbered', 'current_idea_position', 'text'));
    }

    public function bookmark_move(Request $request, Idea $idea)
    {
        $validator = Validator::make($request->all(),[
                'd' => ['required', Rule::in([1, -1]),],
            ]);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $idea = $request->user()->bookmarked_ideas->where('id', $idea->id)->first();
        
        $max = ($idea->community ? $idea->community->bookmark_limit : 300);
        
        if ($request->input('d') == 1) {
            $order = (($idea->pivot->order + (int)$request->input('d')) > $max) ? false : $idea->pivot->order + (int)$request->input('d');
        } else if ($request->input('d') == -1) {
            $order = (($idea->pivot->order + (int)$request->input('d')) < 1) ? false : $idea->pivot->order + (int)$request->input('d');
        }
        
        if ($order && ($idea = $request->user()->bookmarked_ideas->where('id', $idea->id)->first())) {
            
            if(is_egora('community')) {
                $bookmarked = $request->user()->bookmarked_ideas()->where('bookmarks.community_id', $idea->community->id)->get();
            } elseif(is_egora('municipal')) {
                $bookmarked = $request->user()->bookmarked_ideas()->whereNotNull('municipality_id')->get();
            } else {
                $bookmarked = $request->user()->bookmarked_ideas->whereNotNull('nation_id');
            }        

            foreach($bookmarked as $b)
            {
                if ($b->pivot->order == $order) {
                    $b->pivot->order = $b->pivot->order + (-1 * (int)$request->input('d'));
                    $b->pivot->save();
                }
            }            
            
            $idea->pivot->order = $order;
            $idea->pivot->save();
            
        }

        $route = [$request->user()->active_search_names->first()->hash];
        if (isset($idea->community)) {  
            $route['community_id'] = $idea->community->id;
        }

        return redirect()->to(route('users.bookmarked_ideas', $route).'#idea'.$idea->id)
                ->with('success', 'Idea position updated.');
    }
    
    public function move(Request $request, Idea $idea)
    {
        $validator = Validator::make($request->all(),[
                'd' => ['required', Rule::in([1, -1]),],
            ]);

        if(is_egora('community')) {
            $liked = $request->user()->liked_ideas()->where('idea_user.community_id', $idea->community->id)->get();
        } elseif(is_egora('municipal')) {
            $liked = $request->user()->liked_ideas()->whereNotNull('municipality_id')->get();
        } else {
            $liked = $request->user()->liked_ideas->whereNotNull('nation_id');
        }
        
        $all = ip_places();
        list($used, $current) = ip_used_places($liked,$idea);
        $unused = array_diff($all, $used);
        
        $order = false;
        if ($request->input('d') == 1) {
            foreach($unused as $place) {
                $order = $place > $current ? $place: $order;
            }
        }
        
        if ($request->input('d') == -1) {
            foreach($unused as $place) {
                if (!$order) {
                    $order = $place < $current ? $place: $order;
                }
            }
        }
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        if ($order) {
            if ($order>23) {
                $position = $order-23;
            } else {
                $position = 0;
            }

            //$request->user()->liked_ideas()->updateExistingPivot($idea->id, ['position'=>$position, 'order' => $order]);            
                        
            // prev line not saving before event UserIdeologicalProfileChanged started
            if ($liked_idea = $request->user()->liked_ideas->where('id', $idea->id)->first()) {
                $liked_idea->pivot->order = $order;
                $liked_idea->pivot->position = $position;
                $liked_idea->pivot->save();
            }
            
            $route = [$request->user()->active_search_names->first()->hash];
            if (isset($idea->community)) {  
                $route['community_id'] = $idea->community->id;
            }
        
            event(new UserIdeologicalProfileIdeaMoved($request->user(), $idea));

            return redirect()->to(route('users.ideological_profile', $route).'#idea'.$idea->id)
                    ->with('success', 'Idea position updated.');
        }
        
        return redirect()->back()->withError('Please check input.');
    }
    
    
    /**
     * Return leading space to content
     */
    private function _starting_space(Request $request, $name) 
    {
        if (($_POST[$name] !== $request->input($name)) && preg_match('/^(\s+)\S+/', $_POST[$name], $matches) && $matches[1]) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nations = $this->_user_nation($request)->pluck('id');
        
        $rules = [
            'content' => 'required|string|max:64000',
            'position1' => ['required_without:position2', 'nullable', 'numeric', 'min:-6', 'max:46',
                        // fix this for communities
                //        'unique_position:'.$request->user()->id 
                ],            
            'egora_id' => ['nullable', 'in:'.collect(\Arr::pluck(config('egoras'), 'id'))->implode(',') ]
        ];
        
        if (is_egora() || is_egora('community')) {
            $rules['nation'] = 'required_without:community|integer|in:'.implode(',',$nations->toArray());
            $rules['community'] = 'required_without:nation|integer|in:'.implode(',',$request->user()->communities->pluck('id')->toArray());            
        } 

        $validator = Validator::make($request->all(), $rules, [
            'content.max' => 'An idea may not be greater than 64,000 characters.'
        ]);
                
        $order = $request->input('position1');
        if ($request->exists('position1') && $request->input('position1')>23) {
            $position = $request->input('position1')-23;
        } else {
            $position = '0';
        }
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        $ideas = [];
        if(is_egora()) {
            $ideas = $request->user()->liked_ideas->whereNotNull('nation_id');
        } else if(is_egora('community')) {
            $ideas = $request->user()->liked_ideas->where('community_id', $request->input('community'));
        } else if(is_egora('municipality')) {
            $ideas = $request->user()->liked_ideas->whereNotNull('municipality_id');
        }
        
        list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $ideas);
        
        if (in_array($order, $numbered)) {
            return redirect()->back()
                    ->withInput()->withErrors('Position is already taken.');
        }        
        
        $idea = new Idea([
            'content' => $this->_starting_space($request, 'content').$request->input('content'),
            'egora_id' => ($request->has('egora_id') ? $request->input('egora_id') : current_egora_id()),
        ]);
                
        $idea->user()->associate($request->user()->id);
        
        if ($idea->egora_id == config('egoras.default.id')) {
            $idea->nation()->associate($request->input('nation'));
        } else if ($idea->egora_id == config('egoras.community.id')) {
            $idea->community()->associate($request->input('community'));
        } else if ($idea->egora_id == config('egoras.municipal.id')) {
            $idea->municipality()->associate($request->user()->municipality->id);
        }
        
        if ($request->has('idea_id')) {
            $idea->idea_id = $request->input('idea_id');
        }
        
        $idea->save();
        
        $arr =  ['position'=>$position, 'order' => $order];
        if ($idea->egora_id == config('egoras.community.id')) {
            $arr['community_id'] = $request->input('community');
        }
        
        $request->user()->liked_ideas()->syncWithoutDetaching($idea);        
        $request->user()->liked_ideas()->updateExistingPivot($idea->id,$arr);

        event(new UserIdeologicalProfileChanged($request->user(), $idea));
        
        return redirect()->route('users.ideological_profile', array_merge([$request->user()->active_search_name_hash], $arr))->with('success', 'New Idea created');   
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Idea $idea)
    {
       $validator = Validator::make($request->all(),[
            'notification_id' => ['exists:notifications,id'],
            'comment_notification_id' => ['exists:comment_notifications,id'],
            ],
            [
            'notification_id.exists' => 'This notification no longer exists.',
            'comment_notification_id.exists' => 'This notification no longer exists.'
            ]);

        if ($validator->fails()) {
            $a = $validator->getMessageBag()->getMessages();
            $message = array_shift($a);
            
            switch_by_idea($idea);
            $request->session()->flash('message', $message[0]);
            
            return redirect()->route('log.index')
                    ->withInput();
        }

        $ideas = [];
        
        if (auth()->guard('web')->check()) {
            if(is_null($idea->community) && is_null($idea->municipality)) {
                $ideas = $request->user()->liked_ideas->whereNotNull('nation_id');
            } else if(!is_null($idea->community)) {
                $ideas = $request->user()->liked_ideas->where('community_id', $idea->community->id);
            } else if(!is_null($idea->municipality)) {
                $ideas = $request->user()->liked_ideas->whereNotNull('municipality_id');
            }
        }
        
        if (auth()->guard('web')->check()) {
            list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $ideas, $idea);
        } else {
            list($numbered, $current_idea_position) = [null, null];
        }
        
        $current_idea_point_position = $current_idea_position;
        if(!is_null($current_idea_position)) {
            if($current_idea_position>23) {
                $current_idea_point_position = $current_idea_position - 23;
            } else {
                if ($current_idea_position < 0) {
                    $current_idea_point_position = negative_order()[$current_idea_position];
                } else {
                    if (is_null($idea->community) && is_null($idea->municipality)) {
                        $current_idea_point_position = '0 ('.$current_idea_position.')';
                    } else if (is_egora('community')) {
                        $current_idea_point_position = '(' . $current_idea_position . ')' ;                    
                    }
                }
            }
        }
        
        $presets = NotificationPreset::orderBy('order')->get();
        
        $notification = null;
        $notification_id = null;
        $user_notifications = collect();
        $user_notifications_ids = [];
        $notification_response_sent = false;

//        $idea->load('liked_users_visible.active_search_names');
        $idea->load(['liked_users' => function($q){
            $q->with('active_search_names');
            $q->visible()->recent();

            if (!is_egora()) {
                $q->where('idea_user.order', '>=', 0 );
            }
        }, 'moderators' => function($q) {
            $q->with('active_search_names');
            $q->visible()->recent();
            
//            $q->whereHas('user_type', function($q){
//                $q->where('verified', 1);                
//            });
        }]);

        
        if($request->has('notification_id')) {
            $notification = \App\Notification::findOrFail($request->input('notification_id'));
            $notification_id = $notification->id;            
            switch_by_idea($idea);

            $notification_response_sent = $request->user()->user_notifications_new()
                        ->where('idea_id', $idea->id)
                        ->where('sender_id', $request->user()->id)
                        ->where('notification_id', $notification->id)
                        ->first();
            
        } else {
            if ($request->has('comment_notification_id')) {
                $comment_notification = CommentNotification::where('id', $request->input('comment_notification_id'))
                        ->where('receiver_id', $request->user()->id)->first();
                
                if ($comment_notification) {
                    switch_by_idea($idea);
                }
            }
            
            if ($request->has('invitation_response_notification_id')) {
                switch_by_idea($idea);
            }
            
            if ($request->user()) {
                $user_notifications = $request->user()->user_notifications_new()
                        ->where('idea_id', $idea->id)
                        ->whereNull('notification_preset_id')
                        ->get();
                $user_notifications_ids = $user_notifications->pluck('pivot.receiver_id')->toArray();
                
                $load = ['following.liked_ideas','following.active_search_names','notifications_disabled_by'];
                
                if (is_egora()) {
                    $load[] = 'following.nation';
                }elseif (is_egora('municipal')) {
                    $load[] = 'following.municipality';
                }elseif (is_egora('community')) {
                    $load[] = 'following.communities';
                }
                
                $request->user()->load($load);
            }
        }
        
        $order = $request->input('order') ?? 'desc';
        $idea->load(['comments'=> function($q){
            $q->withTrashed();
            $q->counted();
        }]);
        
        $filter = $request->input('filter') ?? 'all';        
        
        if($filter == 'my') {
            $comments = $idea->comments()->where('comments.user_id', $request->user()->id)->orderBy('created_at', $order)->paginate(25);
        } else {
            $comments = $idea->comments()->orderBy('created_at', $order)->paginate(25);
        }
        $comments->load(['user.image', 'comments.user.image', 'user.active_search_names', 'comments.user.active_search_names', 'commentable', 'comments.commentable']);
        
        $open = ($request->has('open') ? (int) $request->input('open') : null);
        
        return view('ideas.view')->with(compact('open', 'filter', 'order', 'comments', 'notification_response_sent', 'user_notifications', 'user_notifications_ids', 'idea', 'numbered', 'current_idea_position', 'current_idea_point_position', 'presets', 'notification', 'notification_id'));
    }

    public function showApi(Request $request, Idea $idea)
    {
        $idea->content = filter_api_text($idea->content);
        
        return response()->json(compact('idea'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Idea $idea)
    {
        $idea->forceDelete();
                
        return redirect()->back()->with('success', 'Idea removed.'); 
    }
    
    public function bookmark(Request $request, Idea $idea) 
    {
        if ($idea->is_bookmarked($request->user())) {
            // unbookmark
            $request->user()->bookmarked_ideas()->detach($idea);
            //fire event idea bookmarks changed
            event(new IdeaUnbookmarked($request->user(), $idea));
            
            $route = [$request->user()->active_search_names->first()->hash];
            if (isset($idea->community)) {  
                $route['community_id'] = $idea->community->id;
            }
                
            return redirect()->to(route('users.bookmarked_ideas', $route).'#idea'.$idea->id)
                    ->with('success', 'Idea unbookmarked.');            
        } else {
            // bookmark
            $max = ($idea->community ? $idea->community->bookmark_limit : 300);
            $spaces = range(1, $max);
                       
            $taken = $request->user()->bookmarked_list($idea)->pluck('pivot.order');            
            $position = collect($spaces)->diff($taken)->shift();
            
            $arr =  ['position'=>$position, 'order' => $position];
            if (isset($idea->community)) {
                $arr['community_id'] = $idea->community->id;
            }
        
            $request->user()->bookmarked_ideas()->syncWithoutDetaching($idea);
            $request->user()->bookmarked_ideas()->updateExistingPivot($idea->id,$arr);
            // fire bookmarked event

            $route = [$request->user()->active_search_names->first()->hash];
            if (isset($idea->community)) {  
                $route['community_id'] = $idea->community->id;
            }

            return redirect()->to(route('users.bookmarked_ideas', $route).'#idea'.$idea->id)
                    ->with('success', 'Idea bookmarked.');            
        }
    }
    
    public function like(Request $request, Idea $idea) 
    {
        $validator = Validator::make($request->all(),[
            'position1' => ['required_without:position2', 'nullable', 'numeric', 'min:-6', 'max:46'],
            'notification_id' => ['exists:notifications,id'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $order = $request->input('position1');
        if ($request->exists('position1') && $request->input('position1')>23) {
            $position = $request->input('position1')-23;
        } else {
            $position = '0';
        }

        $ideas = [];
        if(is_null($idea->community) && is_null($idea->municipality)) {
            $ideas = $request->user()->liked_ideas->whereNotNull('nation_id');
        } else if(!is_null($idea->community)) {
            $ideas = $request->user()->liked_ideas->where('community_id', $idea->community->id);
        } else if(!is_null($idea->municipality)) {
            $ideas = $request->user()->liked_ideas->whereNotNull('municipality_id');
        }
        
        list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $ideas);
        
        if (in_array($order, $numbered)) {
            return redirect()->back()
                    ->withInput()->withErrors('Position is already taken.');
        }
        
        if ($request->exists('notification_id') && ($order >= 0)) {
            $prev_notification = NotificationModel::findOrFail($request->notification_id);
                        
            $notification = new NotificationModel();
            $notification->sender()->associate($request->user());
            $notification->receiver()->associate($prev_notification->sender);
            $notification->idea()->associate($idea);
            $notification->notification_id = $request->notification_id;
//            $notification->notification_preset_id = 0;
            $notification->save();

            event(new UserLikedIdeaFromNotification($notification, ($order >= 0)));
        }

        $arr =  ['position'=>$position, 'order' => $order];
        if (isset($idea->community)) {
            $arr['community_id'] = $idea->community->id;
        }
        
        $request->user()->liked_ideas()->syncWithoutDetaching($idea);
        $request->user()->liked_ideas()->updateExistingPivot($idea->id,$arr);
        
        event(new UserIdeologicalProfileChanged($request->user(), $idea));
        event(new IdeaSupportHasChanged($request->user(), $idea));
        
        $params = [$request->user()->active_search_name_hash];
        if (isset($idea->community)) {
            $params['community_id'] = $idea->community->id;
        }
        
        return redirect()->route('users.ideological_profile', $params)->with('success', 'Order updated');
    }
    
    public function unlike(Request $request, Idea $idea) 
    {
        $params = [$request->user()->active_search_name_hash];
        if (isset($idea->community)) {
            $params['community_id'] = $idea->community->id;
        }
        
//        event(new UserIdeologicalProfileChanged($request->user(), $idea));
        
        $request->user()->liked_ideas()->detach($idea);
        
        event(new IdeaSupportHasChanged($request->user(), $idea));
        
        return redirect()->route('users.ideological_profile', $params)->with('success', 'Idea removed from your IP');   
    }
    
    public function preview(Request $request, $hash)
    {
        $idea_id = intval($hash, 36); // base64_encode / base64_decode
        
        $idea = Idea::findOrFail($idea_id);
        
        $idea->load(['liked_users' => function($q){
            $q->with('active_search_names');
            $q->visible()->recent();
        }]);
        
        switch_by_idea($idea);
        
        if($request->user()) {
            return redirect()->route('ideas.view', [$idea->id, 'preview']);  
        }
        
        return view('ideas.view')->with(compact('idea'));
    }
    
    public function comment(Request $request, Idea $idea)
    {
        $validator = Validator::make($request->all(),[
            'message' => 'required|min:3|max:2300|string',
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $comment = $idea->addComment($request->input('message'), $request->user()->id);
        
        event(new CommentAdded($comment));
        
        return redirect()->to(route('ideas.view', [$idea, 'comments']).'#comment-'.$comment->id)->with('success', 'Comment added.'); 
    }
}
