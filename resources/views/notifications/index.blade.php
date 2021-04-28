@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
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
                        <li>Scroll down to the ‘Invite others’ section.</li>
                        <li>Send the idea to any of your leads by clicking the “Invite”
                            button.</li>
                        <li>The idea will show up in the other user’s Inbox, and they will be
                            able to respond with one of several helpful responses.</li>
                    </ol>
                    <p>If you want other Egora users to be able to contact you to discuss
                    anything in detail, provide your external contact information in your
                    profile.</p>
                </div>
              </div>
            </div>
        </div>
        
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 offset-1 text-center">
                        <h3>{{ __('Idea Notifications') }}</h3>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>

                <div class="card p-5">
                    @forelse($rows as $row)
                    <div id="nid{{$row->id}}" class="mb-3">
                        <div class="pb-2">
                            <div class="row">
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12">
                                        <b>
                                            <a style="color:#000;" href="{{ route('users.ideological_profile', $row->sender->active_search_name_hash) }}">
                                            {{ $row->sender->active_search_names->first()->name ??  $row->sender->id }} 
                                            </a>
                                        </b>
                                        @if ($row->invite)
                                            {{ __('invited you to examine their idea.') }}
                                        @elseif (!$row->sender->liked_ideas->contains($row->idea) && !$row->response)
                                            {{ __('removed their support from this idea.') }}
                                        @elseif ($row->sender->liked_ideas->contains($row->idea) && !$row->response)
                                            {{ __('supported your idea!') }}
                                        @elseif ($row->response)
                                            : {{ $row->notification_preset->title }}
                                        @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            {{ $row->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-right">
                                    @if ($row->invite)
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', [$row->idea->id,'notification_id'=>$row->id]) }}">{{ __('Open') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!$row->invite)
                        <div class="card">
                            <div class="card-body">
                                {!! make_clickable_links(shorten_text($row->idea->content)) !!}
                            </div>
                        </div>
                        @endif
                        <div class="pt-2">
                            <div class="row">
                                <div class="col-3 text-left">
                                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('delete', $row) )
                                    <form action="{{ route('notifications.delete', $row->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete')}}</button>
                                    </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
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

