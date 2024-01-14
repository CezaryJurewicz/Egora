@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 offset-md-1 text-center">
                        <h3>{{ __('Notifications') }}</h3>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>

                <div class="accordion mb-3" id="accordion">
                    <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                      <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                          <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Guide
                          </button>
                        </h2>
                      </div>

                      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body col-md-10 offset-md-1">
                            <p>Sending ideas to other philosophers for review and support is easy and convenient.</p>
                            <ol>
                                <li>In the Philosophers screen, find the other philosopher and go to their profile.</li>
                                <li>Click the “Follow” button to add the philosopher as one of your leads.</li>
                                <li>Open any idea that you want to share with others.</li>
                                <li>Scroll down to the “Invitations” section.</li>
                                <li>Send the idea to any of your leads by clicking the “Invite”
                                    button.</li>
                                <li>The idea will show up in the other philosopher's inbox, and they will be able to respond with one of several helpful preset responses.</li>
                            </ol>
                            <p>If you want other philosophers to be able to contact you directly to discuss anything in detail,
                                provide your external contact information in your profile. Otherwise, you can use the Comments 
                                section under each idea for some general exchanges.</p>
                        </div>
                      </div>
                    </div>
                </div>
                
                <div class="card pl-2 pr-2 pt-2 mt-5">
                    @if ($lines->where('egora_id', current_egora_id())->isNotEmpty())
                        @include('blocks.log.card', ['lines' => $lines->where('egora_id', current_egora_id()), 'eid' => current_egora_id()])   
                    @endif 
                    
                    @foreach($lines->groupBy('egora_id')->diffKeys([current_egora_id() => []])->sortKeys() as  $eid => $nlines)
                        @include('blocks.log.card', ['lines' => $nlines, 'eid' => $eid])
                    @endforeach
                    
                    @if($lines->isEmpty())
                    <div class="p-5"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

