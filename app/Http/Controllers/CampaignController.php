<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Idea;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        
        if ($request->has('nation') && $request->input('nation')) {
            
            $validator = Validator::make($request->all(),[
                'nation' => 'required|exists:nations,title',
            ]);

            $nation = $request->input('nation');
            
            if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }
            
            $users = User::whereHas('campaign')
                ->whereHas('nation', function($q) use ($request) {
                    $q->where('title', $request->input('nation'));
                })->get();

            $user_points = collect();
            
            foreach ($users as $user) {
                $result = \DB::select('select sum(`position`) as `points` from (
                        select sum(`idea_user`.`position`) as `position` from `idea_user` where `idea_id` in (
                            select `ideas`.`id` from `ideas` inner join `idea_user` on `ideas`.`id` = `idea_user`.`idea_id` where `idea_user`.`user_id` = ? and `ideas`.`deleted_at` is null
                        ) and exists (
                            select * from `users` where `idea_user`.`user_id` = `users`.`id` and exists (
                                select * from `user_types` where `users`.`user_type_id` = `user_types`.`id` and `verified` = 1
                            ) and `users`.`deleted_at` is null
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
        
        return view('campaigns.index')->with(compact('rows', 'nation'));   
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
        $campaign = new Campaign();
        $campaign->user()->associate($request->user());
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
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->back()->with('success','Candidacy withdrawn.');
    }
}
