<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller
{
    public function indexApi(Request $request)
    {
        if ($request->input('prefix')) {
            $model = City::where('title', 'like', $request->input('prefix').'%');
        } else {
            $model = new City;
        }
        
        if ($request->input('country')) {
            $model->whereHas('country', function($q) use ($request){
                $q->where('title', $request->input('country'));
            });
        }
        
        $result = $model->get();
        
        return response()->json(compact('result'));
    }
}
