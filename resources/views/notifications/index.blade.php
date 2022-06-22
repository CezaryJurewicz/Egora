@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 offset-md-1 text-center">
                        <h3>{{ __('Idea Notifications') }}</h3>
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
                        <div class="card-body col-md-10 offset-1">
                            <p>Sending ideas to other Egora users for review and support is easy
                                and convenient.</p>
                            <ol>
                                <li>In the Users screen, find any other user and go to their profile.</li>
                                <li>Click the “Follow” button to add the user as one of your leads.</li>
                                <li>Open any idea that you want to share with others.</li>
                                <li>Scroll down to the “Invitations” section.</li>
                                <li>Send the idea to any of your leads by clicking the “Invite”
                                    button.</li>
                                <li>The idea will show up in the other user’s Inbox, and they will be
                                    able to respond with one of several helpful responses.</li>
                            </ol>
                            <p>If you want other Egora users to be able to contact you directly to discuss anything in detail,
                                provide your external contact information in your profile. Otherwise, you can use the Comments 
                                section under each idea for some general exchanges.</p>
                        </div>
                      </div>
                    </div>
                </div>

                <div class="card p-5 mt-5">
                    @forelse($rows as $row)
                        @include('blocks.log.notification', ['row' => $row])   
                    @empty
                        <!--<p>@lang('ideas.No ideas found')</p>-->
                    @endforelse
                    
                    {{  $rows->render() }}
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

