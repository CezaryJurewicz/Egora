<?php

namespace App\Http\Controllers;

use App\Idea;
use App\Nation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\IdeaSupportHasChanged;
use Illuminate\Validation\Rule;
use App\NotificationPreset;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Idea::paginate(100);
        
        return view('ideas.index')->with(compact('ideas'));
    }
    
    public function _ideas(Request $request, $view) 
    {
        $ideas = collect();
        $search = null;
        $relevance = null;
        $community = null;
        $nation = null;
        $unverified = null;
        $nations = $this->_nation_select($request, $view);
        $all_nations = Nation::get();
        
        // if creator is not deactivated
        $model = Idea::query();
        if (!empty($request->all()))
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
                    'community' => ['nullable', 'numeric'],
                    'unverified' => 'nullable|boolean',
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
            
            $model->where(function($q) use ($request, $community, $search, $relevance, $nation){
                if($search) {
                    $q->where('content','like', '%'.$search.'%');
                }
                
                if (is_egora()) {
                    $q->where(function($q) use ($relevance, $nation){
                        if($relevance && $relevance != -1) {
                            $q->where('nation_id', $relevance);
                        } else if ($relevance && $relevance == -1) {
                            $egora = Nation::where('title', 'Egora')->first();
                            $q->where('nation_id', '<>', $egora->id);
                        }

                        if($nation) {
                            $q->orWhereHas('nation', function($q) use ($nation) {
                                $q->where('title', 'like', $nation.'%');
                            });
                        }
                    });
                } elseif (is_egora('community')) {
                    $q->where(function($q) use ($community, $request){
                        if($community && $community != 0) {
                            $q->where('community_id', $community);
                        } else {
                            $q->whereIn('community_id', $request->user()->communities->pluck('id'));                
                        }
                    });
                    
                }
                
                $q->where('egora_id', current_egora_id());                
            });
            
            if (!$request->input('unverified')) {
                $model->whereHas('liked_users.user_type', function($q){
                    $q->where('verified', 1);                
                });
            }
            
            
            $model->withCount(['liked_users' => function($q) use ($request){
                if (!$request->input('unverified')) {
                    $q->whereHas('user_type', function($q){
                        $q->where('verified', 1);                
                    });
                }
                $q->recent();
            }]);

            $subSql = 'select sum(`idea_user`.`position`) from `users` inner join `idea_user` on `users`.`id` = `idea_user`.`user_id` '
                    . 'where `ideas`.`id` = `idea_user`.`idea_id` and exists (select * from `user_types` where `users`.`user_type_id` = `user_types`.`id`';

            if (!$request->input('unverified')) {
                    $subSql .= ' and `verified` = 1';
            }

            $subSql .= ') and `users`.`deleted_at` is null';
            $subSql .= ' and DATEDIFF(now(), `users`.`last_online_at`) < 23';

            $model->selectSub($subSql, 'liked_users_sum');
            
            
            if ($view == 'ideas.popularity_indexes'){
                $model->orderBy('liked_users_count', 'desc');
                $model->orderBy('liked_users_sum', 'desc');
            } else {
                $model->orderBy('liked_users_sum', 'desc');
                $model->orderBy('liked_users_count', 'desc');
            }

            $model->with(['liked_users' => function($q) use ($request) {
                if (!$request->input('unverified')) {
                    $q->whereHas('user_type', function($q){
                        $q->where('verified', 1);                
                    });
                }
                $q->recent();
            }]);
            
            $ideas = $model->paginate(100);

        } else {
            if (is_egora() && isset($request->user()->nation)) {
                $relevance = $request->user()->nation->id;
            }
        }
        
        $user = $request->user();
        
        return view($view)->with(compact('user', 'ideas', 'nations', 'all_nations', 'search', 'relevance', 'unverified', 'nation', 'community'));
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
        
        if(is_egora()) {
            $ideas = $request->user()->liked_ideas->whereNotNull('nation_id');
        } else if(is_egora('community')) {
            $community_id = $request->community_id;
            $ideas = $request->user()->liked_ideas->where('community_id', $request->community_id);
        }
        list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $ideas);
        
        $user = $request->user();
        
        return view('ideas.create')->with(compact('community_id', 'user', 'nations', 'numbered', 'current_idea_position'));
    }
    
    public function copy(Request $request, Idea $idea)
    {        
        $nations = $this->_user_nation($request);
        $community_id = null;
        
        if(is_egora()) {
            $ideas = $request->user()->liked_ideas->whereNotNull('nation_id');
        } else if(is_egora('community')) {
            $community_id = $request->community_id;
            $ideas = $request->user()->liked_ideas->where('community_id', $request->community_id);
        }
        list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $ideas);
        
        $text = $idea->content;

        $user = $request->user();

        return view('ideas.create')->with(compact('community_id', 'user', 'nations', 'numbered', 'current_idea_position', 'text'));
    }

    public function move(Request $request, Idea $idea)
    {
        $validator = Validator::make($request->all(),[
                'd' => ['required', Rule::in([1, -1]),],
            ]);

        
        $all = ip_places();
        list($used, $current) = ip_used_places($request->user()->liked_ideas,$idea);
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

            $request->user()->liked_ideas()->updateExistingPivot($idea->id, ['position'=>$position, 'order' => $order]);

            return redirect()->to(route('users.ideological_profile', auth('web')->user()->active_search_names->first()->hash).'#idea'.$idea->id)
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
        
        $validator = Validator::make($request->all(),[
            'content' => 'required|string|max:23000',
            'nation' => 'required_without:community|integer|in:'.implode(',',$nations->toArray()),
            'community' => 'required_without:nation|integer|in:'.implode(',',$request->user()->communities->pluck('id')->toArray()),
            'position1' => ['required_without:position2', 'nullable', 'numeric', 'min:1', 'max:46',
                        // fix this for communities
                //        'unique_position:'.$request->user()->id 
                ],
        ], [
            'content.max' => 'An idea may not be greater than 23,000 characters.'
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

        $idea = new Idea([
            'content' => $this->_starting_space($request, 'content').$request->input('content'),
            'egora_id' => current_egora_id(),
        ]);
                
        $idea->user()->associate($request->user()->id);
        
        if(is_egora()) {
            $idea->nation()->associate($request->input('nation'));
        } else if (is_egora('community')) {
            $idea->community()->associate($request->input('community'));
        }
        
        $idea->save();
        
        $arr =  ['position'=>$position, 'order' => $order];
        if (is_egora('community')) {
            $arr['community_id'] = $request->input('community');
        }
        
        $request->user()->liked_ideas()->syncWithoutDetaching($idea);        
        $request->user()->liked_ideas()->updateExistingPivot($idea->id,$arr);

        return redirect()->route('users.ideological_profile', array_merge([$request->user()->active_search_names->first()->hash], $arr))->with('success', 'New Idea created');   
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
            ]);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        if (auth()->guard('web')->check()) {
            list($numbered, $current_idea_position) = $this->_numbers_zeros($request, $request->user()->liked_ideas, $idea);
        } else {
            list($numbered, $current_idea_position) = [null, null];
        }
        
        $current_idea_point_position = $current_idea_position;
        if(!is_null($current_idea_position)) {
            if($current_idea_position>23) {
                $current_idea_point_position = $current_idea_position - 23;
            } else {
                $current_idea_point_position = '0 (' . $current_idea_position . ')' ;
            }
        }
        
        $presets = NotificationPreset::all();
        
        $notification = null;
        if($request->has('notification_id')) {
            $notification = \App\Notification::findOrFail($request->input('notification_id'));
        }
        
        return view('ideas.view')->with(compact('idea', 'numbered', 'current_idea_position', 'current_idea_point_position', 'presets', 'notification'));
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
    
    public function like(Request $request, Idea $idea) 
    {
        $validator = Validator::make($request->all(),[
            'position1' => ['required_without:position2', 'nullable', 'numeric', 'min:1', 'max:46'],
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
        
        $request->user()->liked_ideas()->syncWithoutDetaching($idea);
        $request->user()->liked_ideas()->updateExistingPivot($idea->id, ['position'=>$position, 'order' => $order]);
        
        event(new IdeaSupportHasChanged($idea));
        
        return redirect()->route('users.ideological_profile', $request->user()->active_search_names->first()->hash)->with('success', 'Idea added to your IP');   
    }
    
    public function unlike(Request $request, Idea $idea) 
    {
        $request->user()->liked_ideas()->detach($idea);
        
        event(new IdeaSupportHasChanged($idea));
        
        return redirect()->route('users.ideological_profile', $request->user()->active_search_names->first()->hash)->with('success', 'Idea removed from your IP');   
    }
    
}
