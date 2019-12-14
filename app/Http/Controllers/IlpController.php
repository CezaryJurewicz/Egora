<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UserType;
use App\User;
use App\Petition;
use App\Events\PetitionSupportersChanged;

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
    
    public function submit_officer_application(Request $request, User $usr)
    {
        $user = $request->user();
        $name = $user->name;
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|in:'.$name,
            'polis' => 'required|string|max:200',
        ],
        [
            'in' => 'The name you entered does not match your registered name.',
        ]);
        
        if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }
            
        $petition = new Petition();
        $petition->polis = $request->input('polis');
        $petition->user()->associate($user);
        $petition->save();
        
        // Change class to petitioner
        $type = UserType::where('class', 'petitioner')
            ->where('verified', $user->user_type->verified)
            ->where('candidate',  $user->user_type->candidate)
            ->where('former',  $user->user_type->former)
            ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        return redirect()->route('ilp.menu')->with('success', 'Petition started');      
    }
    
    public function cancel_officer_application(Request $request, User $usr)
    {
        $user = $request->user();
        $user->petition->delete();
        
        // Change class to member
        $type = UserType::where('class', 'member')
            ->where('verified', $user->user_type->verified)
            ->where('candidate',  $user->user_type->candidate)
            ->where('former',  $user->user_type->former)
            ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        return redirect()->route('ilp.menu')->with('success', 'Petition canceled');      
    }
    
    public function submit_application(Request $request, User $usr)
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
        
        return redirect()->route('users.ideological_profile', $user->id)->with('success', __('Member declaration has been signed.'));           
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
    
    public function support_officer_application(Request $request, User $user)
    {
        $request->user()->supporting()->sync($user->petition->id);
        event(new PetitionSupportersChanged($user->petition, $request->user()));
        
        return redirect()->back()->with('success', 'Added to petition');
    }
    
    public function unsupport_officer_application(Request $request, User $user)
    {
        $request->user()->supporting()->detach($user->petition->id);
        event(new PetitionSupportersChanged($user->petition, $request->user()));
        
        return redirect()->back()->with('success', 'Removed from petition');
    }
    
    public function withdraw_from_ilp(Request $request, User $user) 
    {
        return view('ilp.withdraw_from_ilp');
    }
    
    public function withdraw_from_ilp_process(Request $request, User $user) 
    {
        $type = UserType::where('class', 'user')
            ->where('verified', $user->user_type->verified)
            ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        return redirect()->route('users.ideological_profile', $user->id)->with('success', 'Withdrawn from ILP.');           
    }
}
