<?php

namespace App\Http\Controllers;

use App\Passport;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class PassportController extends Controller
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
     * @param  \App\Passport  $passport
     * @return \Illuminate\Http\Response
     */
    public function show(Passport $passport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Passport  $passport
     * @return \Illuminate\Http\Response
     */
    public function edit(Passport $passport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Passport  $passport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Passport $passport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Passport  $passport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Passport $passport)
    {
        $media = $passport->image;
        
        if (Storage::disk($media->disk)->exists($media->filename)) {
            Storage::disk($media->disk)->delete($media->filename);
        }
        
        if ($media->delete()){
            if ($passport->delete()){
                return redirect()->back()->with('success','File deleted.');
            }
            
            return redirect()->back()->withErrors(['Passport is not deleted.']);
        }
        
        return redirect()->back()->withErrors(['File is not deleted.']);
        
    }
}
