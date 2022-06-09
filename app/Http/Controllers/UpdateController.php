<?php

namespace App\Http\Controllers;

use App\Update;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'filter' => 'nullable|in:status,idea,comment,all,follower',
        ]); 

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        $filter = $request->input('filter') ?: 'idea';
        
        $lines = Update::
            where(function($q) use ($request, $filter) {
                $q->whereHasMorph('updatable', '*');
                $q->whereHas('user', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                });
// Updates are common through all egoras
//                $q->where('egora_id', current_egora_id());                
            })
            ->orderBy('created_at','asc')
            ->get();
            
        $result = [];
        
        foreach(['status', 'idea', 'follower', 'comment', 'all'] as $title=>$id) {
            if ($id == 'all') {
                $result[$id]  = $lines->whereIn('type', ['comment', 'subcomment']);
            } else {
                $result[$id]  = $lines->where('type', $id);
            }
        }
        
        return response()->view('updates.index', compact('lines','filter','result'))
                ->withHeaders([
                    'Expires' => 'Thu, 19 Nov 1981 08:52:00 GMT',
                    'Cache-Control' => 'no-store, no-cache, must-revalidate'
                ]);
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
            
            switch_to_egora();
            return redirect(route('users.about', [ $hash,'open'=>$updatable_id]).'#comment-'.$updatable_id);
        } else if ($update->type == 'follower') {
            $hash = $update->updatable->active_search_name_hash;
            $update->delete();
            
            switch_to_egora();
            return redirect()->route('users.ideological_profile', [$hash]);
        } else if ($update->type == 'comment' || $update->type == 'subcomment') {
            if ($update->updatable->commentable && $update->updatable->commentable->commentable instanceof \App\User) {
                $redirect = route('users.about', [ $update->updatable->commentable->commentable->active_search_name_hash,'open'=> $update->updatable->commentable->id]).'#comment-'.$update->updatable->id;                                    
            } else if ($update->updatable->is_response()) {
                switch_by_idea($update->updatable->commentable->commentable);
                $redirect = route('ideas.view', ['comments' => 1, $update->updatable->commentable->commentable, 'open'=>$update->updatable->commentable->id]).'#comment-'.$update->updatable->id;
            } else if(!is_null($update->updatable->commentable)) {
                switch_by_idea($update->updatable->commentable);
                $redirect = route('ideas.view', ['comments' => 1, $update->updatable->commentable]).'#comment-'.$update->updatable->id;
            }

            $update->delete();

            return redirect($redirect);
        } else if ($update->type == 'idea') {
            switch_by_idea($update);
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
    public function destroy(Request $request, Update $update)
    {
        $validator = Validator::make($request->all(),[
            'filter' => 'required|in:status,idea,comment,all,follower',
        ]); 

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $filter = $request->input('filter');
        
        $update->delete();
        
        return redirect()->route('updates.index', compact('filter'))->with('success', 'Update removed.'); 
    }
    
    public function destroy_filtered(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'filter' => 'required|in:status,idea,comment,all,follower',
            'egora_id' => 'in:1,2,3',
        ]); 

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $f = [
            'status' => 'status',
            'idea' => 'idea',
            'comment' => 'comment',
            'follower' => 'follower',
        ];
        
        $filter = $request->input('filter') ?: 'status';
        
        $lines = Update::
            where(function($q) use ($request, $filter, $f) {
                $q->whereHas('user', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                });
                
                if (in_array($filter, $f)) {
                    $q->where('type', $f[$filter]);                    
                } else if($filter == 'all') {
                    $q->where(function($q){
                        $q->where('type', 'comment');
                        $q->orWhere('type', 'subcomment');
                    });
                }
                
                if ($request->input('egora_id')) {
                    $q->where('egora_id', $request->input('egora_id'));                
                } else {
                    $q->where('egora_id', current_egora_id());                
                }
            })
            ->delete();
        
        return redirect()->route('updates.index', compact('filter'))->with('success', 'Updates removed.'); 
    }
}
