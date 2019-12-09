<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller
{
    public function indexApi(Request $request)
    {
        $prefix = $request->input('prefix');
        
        if ($prefix) {
            $model = City::where('title', 'like', $prefix.'%');
        } else {
            $model = new City;
        }
        
        $result = $model->get();
        
        return response()->json(compact('result'));
    }
}
