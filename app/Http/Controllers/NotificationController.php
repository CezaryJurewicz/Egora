<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notification as NotificationModel;
use App\Events\UserRespondedToInvitation;

class NotificationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = NotificationModel::whereHas('receiver', function($q) use ($request) {
                    $q->where('id', $request->user()->id);
                })
                ->paginate(100);
        
        return view('notifications.index')->with(compact('rows'));
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
            'idea_id' => ['required', 'exists:ideas,id'],
            'preset_id' => ['required', 'exists:notification_presets,id'],
            'notification_id' => ['required','exists:notifications,id']
            ]);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()->withErrors($validator);
        }
        
        $prev_notification = NotificationModel::findOrFail($request->notification_id);
        
        $notification = new NotificationModel();
        $notification->sender()->associate($request->user());
        $notification->receiver()->associate($prev_notification->sender);
        $notification->idea()->associate($prev_notification->idea);
        $notification->notification_id = $request->notification_id;
        
        if ($request->has('preset_id')) {
            $notification->notification_preset_id = $request->preset_id;
        }
        $notification->save();

        event(new UserRespondedToInvitation($notification));
        
        return redirect()->back()->with('success', 'Response sent.');
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotificationModel $notification)
    {
        $notification->delete();
                
        return redirect()->back()->with('success', 'Notification removed.'); 
    }
}
