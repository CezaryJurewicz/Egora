<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\CommentAdded;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(),[
            'message' => 'required|min:3|max:2300|string',
        ]);
         
        if ($validator->fails()) {  
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $comment->message = $request->input('message');
        $comment->save();

        if ($comment->commentable instanceof \App\Comment) {  
            $idea = $comment->commentable->commentable;
        } else {
            $idea = $comment->commentable;
        }
        
        return redirect()->to(route('ideas.view', [$idea, 'comments']).'#comment-'.$comment->id)->with('success', 'Comment updated.'); 
    }

    public function comment(Request $request, Comment $comment)
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
         
        return redirect()->to(route('ideas.view', [$comment->commentable, 'comments']).'#comment-'.$comment->id)->with('success', 'Comment added.'); 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->commentable instanceof \App\Comment) {  
            $item = $comment->commentable->commentable;
            $comment->forceDelete();
        } else {
            $item = $comment->commentable;
            $comment->forceDelete();
        }
        
        if ($item instanceof \App\User) {
            return redirect()->back()->with('success', 'Successfully deleted.');
        }
        
        return redirect()->to(route('ideas.view', [$item, 'comments']).'#my-tab-content')->with('success', 'Comment deleted.'); 
    }
    
    public function moderate(Request $request, Comment $comment, $action)
    {
        $votes = $comment->votes()->where('user_id',$request->user()->id)->get();
        
        if ($action == 'keep') {
            $add = 1;
        } elseif ($action == 'delete') {
            $add = -1;
        }
        
        if ($votes->isNotEmpty()) {
            $vote = $votes->first()->pivot->vote;
            
            if ($vote != $add) {
                $comment->votes()->syncWithoutDetaching([$request->user()->id => ['vote' => $add]]);
                $comment->score = $comment->score + $add;
                $comment->save();
                
                if ($comment->is_response() && $comment->score == -5) {
                    $comment->forceDelete();
                    return response(['error'=>'Comment deleted.', 'deleted'=>1], 200);
                } else if (!$comment->is_response() && $comment->score == -23) {
                    $comment->delete();
                    return response(['error'=>'Comment deleted.', 'deleted'=>1], 200);
                }
            } else {
                return response(['error'=>'Already voted.'], 403);
            }
        } else {
            $comment->votes()->attach($request->user()->id, ['vote' => $add]);
            $comment->score = $comment->score + $add;
            $comment->save();
        }
                
        return response(['message'=>'Score changed.', 'score' => $comment->score], 200);
    }
}
