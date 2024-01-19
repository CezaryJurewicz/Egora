<?php

namespace App\Http\Controllers;

use App\LogLine;
use Illuminate\Http\Request;

class LogLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lines = LogLine::
            where(function($q) use ($request) {                
                $q->whereHas('user', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                });
                $q->Notifications();
                $q->new();
            })
            ->orWhere(function($q) use ($request) {
                $q->whereHas('user', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                });
                $q->Comments();
            })
            ->orWhere(function($q) use ($request) {
                $q->whereHas('user', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                });
                $q->Bookmarks();
            })
            ->orderBy('created_at','asc')
            ->get();

        foreach($lines as &$line)
        {
            if ($line->loggable instanceof \App\Notification) {
                $line->loggable->notification_response_sent = $request->user()->user_notifications_new()
                        ->where('idea_id', $line->loggable->idea->id)
                        ->where('sender_id', $request->user()->id)
                        ->where('notification_id', $line->loggable->id)
                        ->first();
            }            
        }
            
        return view('log.index')->with(compact('lines'));

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
     * @param  \App\LogLine  $logLine
     * @return \Illuminate\Http\Response
     */
    public function show(LogLine $logLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LogLine  $logLine
     * @return \Illuminate\Http\Response
     */
    public function edit(LogLine $logLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LogLine  $logLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogLine $logLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LogLine  $logLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogLine $logLine)
    {
        //
    }
}
