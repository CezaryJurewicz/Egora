<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApprovalRating;
use Illuminate\Support\Facades\DB;
use App\Idea;

class ApprovalRatingController extends Controller
{
    public function __construct()
    {
//        $this->middleware(['can:vote,App\Idea'], ['only' => ['voteApi']]);
    }
    
    private $dummy = [
        '-3' => ['score' => -3, 'avg' => 0, 'quantity' => 0, 'color' => '#ff0000'],
        '-2' => ['score' => -2, 'avg' => 0, 'quantity' => 0, 'color' => '#ff9900'],
        '-1' => ['score' => -1, 'avg' => 0, 'quantity' => 0, 'color' => '#ffff00'],
        '0' => ['score' => 0, 'avg' => 0, 'quantity' => 0, 'color' => '#34a853'],
        '1' => ['score' => 1, 'avg' => 0, 'quantity' => 0, 'color' => '#46bdc6'],
        '2' => ['score' => 2, 'avg' => 0, 'quantity' => 0, 'color' => '#0000ff'],
        '3' => ['score' => 3, 'avg' => 0, 'quantity' => 0, 'color' => '#9900ff'],
    ];

    public function indexApi(Request $request)
    {
        $user = null;
        if (auth('api')->check()) { 
            $user = auth('api')->user();
        }        
        
        $model = ApprovalRating::select('score')
            ->whereHas('idea', function($q) use ($request){
                $q->where('id', $request->input('id')); 
            });
        $avg = number_format($model->avg('score'),3);
        $total = $model->count();

        if ($user) {
            $selected = ApprovalRating::select('score')
            ->where(function($q) use ($request, $user){
                $q->whereHas('idea', function($q) use ($request){
                    $q->where('id', $request->input('id')); 
                });
                $q->whereHas('user', function($q) use ($request, $user){
                    $q->where('id', $user->id); 
                });
            })->pluck('score')->first();
        } else {
            $selected = null;
        }
        
        $model = ApprovalRating::select('score',DB::raw('count(*) as quantity'))
            ->whereHas('idea', function($q) use ($request){
                $q->where('id', $request->input('id')); 
            })
            ->groupBy('score')
            ->orderBy('score','asc');
            
        $result = $model->get();
        
        foreach($result as $row) {
            $this->dummy[$row->score]['avg'] = $row->quantity / ($total ?? 1);
            $this->dummy[$row->score]['quantity'] = $row->quantity;
            $this->dummy[$row->score]['tooltip'] = 'Votes: '. $row->quantity;
        }
        
        $cols = collect($this->dummy);
       
        if ($user) {
            $vote_allowed = $user->can('vote', Idea::findOrFail($request->input('id')));
        } else {
            $vote_allowed = 0;
        }
        
        return response()->json(['selected' => $selected ?? 0, 'avg'=> $avg ?? 0, 'vote_allowed'=> $vote_allowed, 'total' => $total, 'cols' => $cols->values()->all()]);
    }
    
    public function voteApi(Request $request)
    {
        if ($request->user()->can('vote', Idea::findOrFail($request->input('id')))) {
            $result = ApprovalRating::updateOrCreate(
                    ['user_id' => $request->user()->id, 'idea_id' => $request->input('id') ],
                    ['user_id' => $request->user()->id, 'idea_id' => $request->input('id'), 'score' => $request->input('score') ]
            );

            return response()->json($result);    
        }
    }
}
