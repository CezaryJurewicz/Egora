<?php

namespace App\Http\Controllers;

use App\Idea;
use App\Nation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Idea::get();
        
        return view('ideas.index')->with(compact('ideas'));
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
        $ideas = Idea::where('content','like', '%'.$search.'%')->get();
        
        return view('ideas.index')->with(compact('ideas'));
    }
    
    private function _user_nation(Request $request)
    {
        $n = ['Universal'];
        
        if ($request->user()->user_type->class == 'user') {
            $n[] = 'Egora';
        }
        
        $nations = Nation::whereIn('title', $n)->get();
        $nations->push($request->user()->nation);

        return $nations;
    }
    
    private function _numbers_zeros(Request $request, $ideas)
    {
        $numbered = [];
        $zeros = [];
        foreach($ideas as $i) 
        {
            $position = ($i->pivot) ? $i->pivot->position: $i->position;
            
            if($position>0) {
                $numbered[] = $position;
            } else {
                $zeros[] = count($zeros)+1;
            }
        }
        
        return [$numbered, $zeros];
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $nations = $this->_user_nation($request);
        
        list($numbered, $zeros) = $this->_numbers_zeros($request,$request->user()->ideas);
        
        return view('ideas.create')->with(compact('nations', 'numbered', 'zeros'));
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
            'content' => 'required|string',
            'nation' => 'required|integer|in:'.implode(',',$nations->toArray()),
//            'position' => 'required|integer|between:0,23',
            'position1' => ['required_without:position2', 'nullable', 'numeric', 'min:1', 'max:23'],
            'position2' => ['required_without:position1', 'nullable', 'numeric', 'min:0', 'max:1'],
        ]);
         
        
        if ($request->exists('position1') && $request->input('position1')>0) {
            $position = $request->input('position1');
        } else {
            $position = '0';
        }
        
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $idea = new Idea([
            'content' => $request->input('content'),
            'position' => $position,
        ]);
                
        $idea->user()->associate($request->user()->id);
        $idea->nation()->associate($request->input('nation'));
        
        $idea->save();
        
        return redirect()->back()->with('success', 'New Idea created');   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Idea $idea)
    {
        list($numbered, $zeros) = $this->_numbers_zeros($request, $request->user()->liked_ideas);
        
        return view('ideas.view')->with(compact('idea', 'zeros', 'numbered'));
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
        //
    }
    
    public function like(Request $request, Idea $idea) 
    {
        $validator = Validator::make($request->all(),[
            'position1' => ['required_without:position2', 'nullable', 'numeric', 'min:1', 'max:23'],
            'position2' => ['required_without:position1', 'nullable', 'numeric', 'min:0', 'max:1'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ($request->exists('position1') && $request->input('position1')>0) {
            $position = $request->input('position1');
        } else {
            $position = '0';
        }
        
        $request->user()->liked_ideas()->syncWithoutDetaching($idea);
        $request->user()->liked_ideas()->updateExistingPivot($idea->id, ['position'=>$position]);
        
        return redirect()->back()->with('success', 'Idea added to liked list');   
    }
    
}
