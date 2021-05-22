<?php

namespace App\Http\Controllers;

use App\Subdivision;
use Illuminate\Http\Request;

class SubdivisionController extends Controller
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
    
    public function indexApi(Request $request)
    {
        $prefix = $request->input('prefix');
        
        if ($prefix) {
            $model = Subdivision::where('title', 'like', $prefix.'%')->where('nation_id', $request->user()->nation->id);
        } else {
            $model = new Subdivision;
        }
        
        $result = $model->get();
        
        return response()->json(compact('result'));
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
     * @param  \App\Subdivision  $subdivision
     * @return \Illuminate\Http\Response
     */
    public function show(Subdivision $subdivision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subdivision  $subdivision
     * @return \Illuminate\Http\Response
     */
    public function edit(Subdivision $subdivision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subdivision  $subdivision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subdivision $subdivision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subdivision  $subdivision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subdivision $subdivision)
    {
        //
    }
}
