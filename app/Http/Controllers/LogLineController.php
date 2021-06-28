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
        $lines = LogLine::whereHas('user', function($q) use ($request) {
            $q->where('id', $request->user()->id);
        })
        ->where('egora_id', current_egora_id())
        ->new()->paginate(100);

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
