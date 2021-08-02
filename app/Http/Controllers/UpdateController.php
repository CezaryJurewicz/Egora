<?php

namespace App\Http\Controllers;

use App\Update;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lines = Update::
            where(function($q) use ($request) {
                $q->whereHas('user', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                });
                $q->where('egora_id', current_egora_id());
            })
            ->orderBy('created_at','asc')
            ->paginate(100);

        return view('updates.index')->with(compact('lines'));
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
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function show(Update $update)
    {
        //        
    }
    
    public function redirect(Update $update)
    {
        if ($update->type == 'status') {
            $hash = $update->updatable->commentable->active_search_name_hash;
            $updatable_id = $update->updatable->id;
            $update->delete();

            return redirect(route('users.about', [ $hash,'open'=>$updatable_id]).'#comment-'.$updatable_id);
        } else if ($update->type == 'follower') {
            $hash = $update->updatable->active_search_name_hash;
            $update->delete();
            
            return redirect()->route('users.ideological_profile', [$hash]);
        } else if ($update->type == 'comment') {
            if ($update->updatable->commentable && $update->updatable->commentable->commentable instanceof \App\User) {
                $redirect = route('users.about', [ $update->updatable->commentable->commentable->active_search_name_hash,'open'=> $update->updatable->commentable->id]).'#comment-'.$update->updatable->id;                                    
            } else if ($update->updatable->is_response()) {
                $redirect = route('ideas.view', ['comments' => 1, $update->updatable->commentable->commentable, 'open'=>$update->updatable->commentable->id]).'#comment-'.$update->updatable->id;
            } else if(!is_null($update->updatable->commentable)) {
                $redirect = route('ideas.view', ['comments' => 1, $update->updatable->commentable]).'#comment-'.$update->updatable->id;
            }

            $update->delete();

            return redirect($redirect);
        } else if ($update->type == 'idea') {
            $update->delete();
            
            return redirect()->route('ideas.view', [$update->updatable->id]);
        }
        
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function edit(Update $update)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Update $update)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function destroy(Update $update)
    {
        $update->delete();
        
        return redirect()->back()->with('success', 'Update removed.'); 
    }
}
