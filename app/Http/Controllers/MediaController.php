<?php

namespace App\Http\Controllers;

use App\Media;
use App\Passport;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
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
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:jpeg,jpg,png',
            'type' => 'required|in:image,passport'
        ],[
            'file.mimes'     => 'Uploaded file is not an Image format.',
            'file.required'  => 'File is required.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->messages());
        }

        if (!is_null($request->file('file')) && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $hash = sha1_file($file->path());

            $extention = $file->getClientOriginalExtension();
            $storage_disk = env('STORAGE_DISK','local');
            $filename = uniqid().".$extention";

//            $imported = Media::where('hash', $hash)->first();
            $imported = null;
            if (is_null($imported)) {
                Storage::disk($storage_disk)->makeDirectory('public'.DIRECTORY_SEPARATOR.$request->type);
                if (Storage::disk($storage_disk)->putFileAs('public'.DIRECTORY_SEPARATOR.$request->type, $file, $filename)) {
                    Storage::disk($storage_disk)->setVisibility('public'.DIRECTORY_SEPARATOR.$request->type.DIRECTORY_SEPARATOR.$filename, 'public'); 
                    $data = [
                        'filename' => 'public'.DIRECTORY_SEPARATOR.$request->type.DIRECTORY_SEPARATOR.$filename,
                        'original_name' => $file->getClientOriginalName(),
                        'hash' => $hash,
                        'mime' => $file->getClientMimeType(),
                        'disk' => $storage_disk,
                        'user_id' => $request->user()->id
                    ];
                      
                    if ($request->type == 'image') {
                        if ($request->user()->image()->create($data)) {
                            return redirect()->back()->with('success','File uploaded.');
                        }
                    } else if ($request->type == 'passport') {
                        $passport = new Passport;
                        $passport->user()->associate($request->user());
                        $passport->save();

                        $media = new Media($data);
                        $media->mediable()->associate($passport);
                        $media->save();

                        if ($media->save()) {
                            return redirect()->back()->with('success','File uploaded.');
                        }
                    }
                }
            }

            return redirect()->back()->withErrors(['File is imported already.']);
        }
        return redirect()->back()->withErrors(['File is not found.']);
    }
        
    /**
     * Display the specified resource.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media)
    {
        if (Storage::disk($media->disk)->exists($media->filename)) {
            Storage::disk($media->disk)->delete($media->filename);
        }
        
        if ($media->delete()){
            return redirect()->back()->with('success','File deleted.');
        }
        
        return redirect()->back()->withErrors(['File is not deleted.']);
    }
}
