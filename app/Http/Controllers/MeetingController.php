<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Country;
use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $countries = Country::with(['cities'=>function($q){ 
                $q->with(['meetings'=>function($q){
                    $q->where('start_at','>', Carbon::now());
                    $q->whereHas('user');
                    $q->with('user.search_names');
                }]); 
                $q->whereHas('meetings', function($q){
                    $q->where('start_at','>', Carbon::now());
                });
            }])
            ->whereHas('cities.meetings', function($q){
                $q->where('start_at','>', Carbon::now());                                                                           
            })
            ->get();
                
        foreach ($countries as &$country) {
            foreach($country->cities as &$city) {
                $city['dates'] = collect();
                foreach($city->meetings as $meeting) {
                    if (!isset($city['dates'][$meeting->start_at->format('Y-m-d')])){
                        $city['dates'][$meeting->start_at->format('Y-m-d')] = collect();
                    }
                    $city['dates'][$meeting->start_at->format('Y-m-d')]->push($meeting);
                }
            }
        }
                
        return view('meetings.index')->with(compact('countries'));   
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
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', 'after:yesterday'], //TODO: create custom rule
            'time' => ['required', 'date_format:"H:i"'],
            'address' => ['required', 'string'],
            'topic' => ['required', 'string', 'max:255'],
            'comments' => ['nullable', 'string'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        $country = Country::where('title', $request->country)->first();
                
        if (is_null($country)) {
            $country = Country::create([
               'title' => $request->country
            ]);
        }

        $city = City::where('country_id', $country->id)
                ->where('title', $request->city)
                ->first();
        
        if (is_null($city)) {
            $city = new City();
            $city->title = $request->city;
            $city->country()->associate($country);
            $city->save();
        }

        $meeting = new Meeting();
        $meeting->start_at = Carbon::parse($request->date.' '.$request->time)->format('Y-m-d H:i:s');
        $meeting->address = $request->address;
        $meeting->topic = $request->topic;
        $meeting->comments = $request->comments;

        $meeting->city()->associate($city);
        $meeting->user()->associate($request->user());
        $meeting->save();
        
        return redirect()->route('meetings.index')->with('success', 'New meeting created.');   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meeting $meeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return redirect()->route('meetings.index')->with('success', 'Meeting deleted.');
    }
}
