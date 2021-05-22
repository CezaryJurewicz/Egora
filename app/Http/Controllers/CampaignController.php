<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Idea;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nation = null;
        $rows = collect();
        $subdivision = 0;

        $subdivisions=[];
        foreach( $request->user()->subdivisions as $i=>$s) {
            $subdivisions[$s->pivot->order] = $s;
        }
        
        if ($request->isMethod('put')) {
            
            $validator = Validator::make($request->all(),[
                'subdivision' => [
                    'required',
                    Rule::in(array_merge([0], $request->user()->subdivisions->pluck('pivot.order')->toArray())),
                ]
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }

            $subdivision = $request->input('subdivision');            
            $users = User::recent()->whereHas('campaign', function($q) use ($request, $subdivision, $subdivisions) {
                    if ($subdivision) {
                        $q->whereHas('subdivision', function($q) use ($request, $subdivision, $subdivisions) {
                            $q->where('id', $subdivisions[$subdivision]->id);
                        });
                    } 
                    $q->where('order', $subdivision);
                })->get();
                
            $user_points = collect();
            
            foreach ($users as $user) {
                $result = \DB::select('select sum(`position`) as `points` from (
                        select sum(`idea_user`.`position`) as `position` from `idea_user` where `idea_id` in (
                            select `ideas`.`id` from `ideas` inner join `idea_user` on `ideas`.`id` = `idea_user`.`idea_id` where `idea_user`.`user_id` = ? and `ideas`.`deleted_at` is null
                                and `idea_user`.`order` >= 0 
                        ) and exists (
                            select * from `users` where `idea_user`.`user_id` = `users`.`id` and exists (
                                select * from `user_types` where `users`.`user_type_id` = `user_types`.`id` and `verified` = 1
                            ) and `users`.`deleted_at` is null
                            and DATEDIFF(now(), `users`.`last_online_at`) < 23
                        )
                        group by `idea_user`.`idea_id`
                        order by `position` desc
                        limit 23
                    ) as `p`', [$user->id]);
                
                if ($result && is_array($result) && isset($result[0]) && $result[0]->points) {
                    $user_points->push([
                        'user_id' => $user->id,
                        'search_name' => $user->active_search_names->first()->name ?? '-',
                        'hash' => $user->active_search_names->first()->hash,
                        'points' =>$result[0]->points
                    ]);
                }
            }
            
            $rows = $user_points->sortByDesc('points')->groupBy('points');
        } 

        return view('campaigns.index')->with(compact('rows', 'nation', 'subdivisions', 'subdivision'));   
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
            'subdivision' => [
                'required',
                Rule::in(array_merge([0], $request->user()->subdivisions->pluck('pivot.order')->toArray())),
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }

        $subdivision_id = null;
        if ($request->input('subdivision')) {
            $subdivision = $request->user()->subdivisions()->where('order', $request->input('subdivision'))->first();
            $subdivision_id = $subdivision->id;
        }
        
        $campaign = new Campaign();
        $campaign->user()->associate($request->user());
        $campaign->subdivision_id = $subdivision_id;
        $campaign->order = $request->input('subdivision');
        $campaign->save();
        
        return redirect()->back()->with('success','Candidacy announced.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        return view('campaigns.view')->with(compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Campaign $campaign)
    {
        $validator = Validator::make($request->all(),[
            'password' => ['required', 'string', 'password'],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $campaign->delete();
        return redirect()->back()->with('success','Candidacy withdrawn.');
    }
}
