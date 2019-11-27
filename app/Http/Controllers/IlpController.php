<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UserType;
use App\User;

class IlpController extends Controller
{
    public function index() 
    {
        return view('ilp.index');
    }
    
    public function menu() 
    {
        return view('ilp.menu');
    }
    
    public function principles() 
    {
        return view('ilp.principles');
    }
    
    public function guide() 
    {
        return view('ilp.guide');
    }
    
    public function officer_petition() 
    {
        return view('ilp.officer_petition');
    }
    
    public function submit_officer_application(Request $request, User $user)
    {
        
    }
    
    public function submit_application(Request $request, User $user)
    {
        $user = $request->user();
        $name = $user->name;
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|in:'.$name,
        ],
        [
            'in' => 'The name you entered does not match your registered name.',
        ]);
        
        if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }
            
        $type = UserType::where('class', 'member')
            ->where('verified', $user->user_type->verified)
            ->where('candidate', 0) //Accepr member ILP declaration
            ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        return redirect()->back()->with('success', 'Application submitted');           
    }
    
    public function accept_application(User $user)
    {
        $type = UserType::where('class', 'member')
            ->where('verified', $user->user_type->verified)
            ->where('candidate', 0)
            ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        return redirect()->back()->with('success', 'Application accepted');           
    }
}
