<?php

namespace App\Http\Controllers;

use App\CommentNotification;
use Illuminate\Http\Request;

class CommentNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = CommentNotification::whereHas('receiver', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                })
                ->where('egora_id', current_egora_id())
                ->paginate(100);
                
        return view('comment_notifications.index')->with(compact('rows'));
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
     * @param  \App\CommentNotification  $commentNotification
     * @return \Illuminate\Http\Response
     */
    public function show(CommentNotification $commentNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommentNotification  $commentNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(CommentNotification $commentNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommentNotification  $commentNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommentNotification $commentNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommentNotification  $commentNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommentNotification $commentNotification)
    {
        $commentNotification->forceDelete();
                
        return redirect()->route('comment_notifications.index')->with('success', 'Notification removed.'); 
    }
}
