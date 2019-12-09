<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;

class CountryController extends Controller
{
    public function indexApi(Request $request)
    {
        $prefix = $request->input('prefix');
        
        if ($prefix) {
            $model = Country::where('title', 'like', $prefix.'%');
        } else {
            $model = new Country;
        }
        
        $result = $model->get();
        
        return response()->json(compact('result'));
    }
}
