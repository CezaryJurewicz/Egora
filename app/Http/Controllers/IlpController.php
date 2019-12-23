<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UserType;
use App\User;
use App\Petition;
use App\Events\PetitionSupportersChanged;
use App\Events\UserLeftIlp;
use Illuminate\Support\Facades\Cookie;

class IlpController extends Controller
{
    public function index() 
    {
        return view('ilp.index');
    }
    
    public function menu(Request $request) 
    {
        $show = false;
        
        if ($request->user()->user_type->isOfficer) {
            if (is_null($request->cookie('cmsgvd'))) {
                $show = true;
                
                
            }
        }
        
        Cookie::queue(Cookie::make('cmsgvd', 'true',  time() + (10 * 365 * 24 * 60 * 60)));
        
        return view('ilp.menu')->with(compact('show'));
    }
    
    public function principles() 
    {
        return view('ilp.principles');
    }
    
    public function founding_members() 
    {
        return view('ilp.founding_members');
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
        return view('ilp.cancel_officer_petition');
    }
    
    public function cancel_officer_application_proceed(Request $request, User $usr)
    {
        $user = $request->user();
        $name = $user->name;
        $polis = $user->petition->polis;
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|in:'.$name,
            'polis' => 'required|in:'.$polis,
        ]);
        
        if ($validator->fails()) {
                return redirect()->back()
                        ->withInput()->withErrors($validator);
            }

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
        if ($request->user()->supporting->isNotEmpty()) {
            return redirect()->back()->withErrors(['You already supporting one petition.']);
        }
        
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
        $type = UserType::where('class', 'member')
            ->where('verified', $user->user_type->verified)
            ->where('former', 1)
            ->first();
        
        $user->user_type()->associate($type);
        $user->save();
        
        event(new UserLeftIlp($user));
        
        return redirect()->route('users.ideological_profile', $user->id)->with('success', 'Withdrawn from ILP.');           
    }
}
