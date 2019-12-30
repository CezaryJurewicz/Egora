<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AdminEmailChange;
use App\Notifications\AdminEmailChanged;

class AdminController extends Controller
{
    public function settings(Request $request)
    {
        $user = $request->user();
        
        return view('admin.settings')->with(compact('user'));
    }
    
    public function update_password(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(),[
            'current' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ( Hash::check($request->current, $user->password) ) {
        
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('admin.settings', $request->user()->id)->with('success', 'Password updated!');   
        }
        
        return redirect()->route('admin.settings', $request->user()->id)->withErrors('Current password doesn\'t match!');
    }
    
    public function update_email_send_token(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);
         
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        if ($request->email !== $user->email) {
            $user->another_email = $request->email;
            $user->email_token = \Illuminate\Support\Str::random(60);

            if ($user->save()) {
                $user->notify(new AdminEmailChange());

                return redirect()->back()->with('success', 'Message sent, please check your current email.');   
            }
            
        } else {
            return redirect()->back()->withErrors('Write new email in a box');   
        }
        
        return redirect()->back()->withErrors('Email update failed!');        
    }

    public function update_email(Request $request, string $token) 
    {
        $user = Admin::where('id', $request->user()->id)
                ->where('email_token', $token)->first();
        
        if ($user) {
            $user->email = $user->another_email;
            $user->another_email = null;
            $user->email_token = null;
            $user->save();

            $user->notify(new AdminEmailChanged());
            
            return redirect()->route('admin.settings', $user->id)->with('success', 'Email changed.');
        }
        
        return redirect()->back()->withErrors('Incorrect signed user and email token.');
    }
    
    public function update_email_confirm(Request $request, string $token) 
    {
        $user = Admin::where('id', $request->user()->id)
                ->where('email_token', $token)->first();
        
        if ($user) {
            return view('admin.update_email_confirm')->with(compact('user', 'token'));
        }
        
        return redirect()->back()->withErrors('Incorrect signed user and email token.');
    }
}
