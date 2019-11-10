<?php

namespace App\Http\Controllers;

use App\SearchName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchNameController extends Controller
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
        return view('search_name.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3|string',
            'seachable' => 'boolean',
            'active' => 'boolean',
        ]);
         
        if ($validator->fails()) {
            return redirect()->back() //route('search_name.create')
                    ->withInput()->withErrors($validator);
        }

        $search_name = new SearchName();        
        $search_name->name = $request->input('name');
        $search_name->seachable = $request->input('seachable')?:0;
        $search_name->active = $request->input('active')?:0;
        
        $search_name->user()->associate($request->user());
        
        $search_name->save();
        
        return redirect()->route('users.view', $request->user()->id)->with('status', 'Search Name created!');   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SearchName  $searchName
     * @return \Illuminate\Http\Response
     */
    public function show(SearchName $searchName)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SearchName  $searchName
     * @return \Illuminate\Http\Response
     */
    public function edit(SearchName $searchName)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SearchName  $searchName
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SearchName $searchName)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SearchName  $searchName
     * @return \Illuminate\Http\Response
     */
    public function destroy(SearchName $searchName)
    {
        //
    }
}
