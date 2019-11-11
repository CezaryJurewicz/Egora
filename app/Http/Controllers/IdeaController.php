<?php

namespace App\Http\Controllers;

use App\Idea;
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
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function show(Idea $idea)
    {
        return view('ideas.view')->with(compact('idea'));
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
}
