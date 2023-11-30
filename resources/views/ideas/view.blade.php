@extends('layouts.app')

@section('content')                    
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="text-center">
                    
                    @if (auth('web')->check())
                        <h3>Idea: Open</h3>
                    @else 
                        @if( \Route::currentRouteName() == 'ideas.preview' )
                            <h3>Idea: Preview</h3>
                        @endif
                    @endif
                </div>
                
                @if (auth('web')->check())
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <!--<a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>-->
                    </div>
                    <div class="offset-6 col-md-3 mb-3">
                        <a href="{{ route('ideas.copy', $idea) }}" class='btn btn-primary btn-sm btn-block'>{{__('some.Copy and Edit')}}</a>
                    </div>
                </div>
                @endif
                <div class="card" id="idea-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                            Relevance: &nbsp;&nbsp;&nbsp;
                            @if (is_null($idea->community) && is_null($idea->municipality))
                                @if (isset($idea->nation))
                                {{ $idea->nation->title }} 
                                @endif
                            @elseif (!is_null($idea->community))
                                {{ $idea->community->title }}                                                        
                            @elseif (!is_null($idea->municipality))
                                {{ $idea->municipality->title }}                             
                            @endif
                            </div>
                            
                            @if (auth('web')->check())
                            @if(isset($current_idea_position) && !is_null($current_idea_position))
                            <div class="col-md-2">
                                <a class='btn btn-primary btn-sm ml-2' href="{{ route('ideas.unlike', $idea->id) }}">{{__('some.Remove and Close')}}</a>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    <div id="idea-div" ideaid="{{$idea->id}}">
                        <div class="card-body" id="idea-text">
                        {!! filter_text($idea->content) !!}
                        </div>

                        @if( \Route::currentRouteName() != 'ideas.preview' )
                        <div class="card-footer pt-4 pb-4">
                            <div class="row" id="source-derivative">
                                <div class="col-8">
                                Source Idea: 
                                @if ($idea->source)
                                <a href="{{ route('ideas.preview', preview_id($idea->source->id)) }}">{{$idea->source->id}}</a>

                                @endif
                                </div>
                                <div class="col-4">
                                Derivative Ideas: 
                                @if ($idea->derivatives->isNotEmpty())
                                    @if ($idea->egora_id == config('egoras.default.id'))
                                    <a href="{{ route('ideas.indexes', ['relevance'=>-2, 'source_id'=>$idea->id])}}">IDI</a>
                                    @else 
                                        @if (!is_null($idea->community))
                                        <a href="{{ route('ideas.popularity_indexes', ['community'=>$idea->community->id, 'source_id'=>$idea->id])}}">IPI</a>
                                        @elseif (!is_null($idea->municipality))
                                        <a href="{{ route('ideas.popularity_indexes', ['relevance'=>-1, 'source_id'=>$idea->id])}}">IPI</a>
                                        @else
                                        <a href="{{ route('ideas.popularity_indexes', ['source_id'=>$idea->id])}}">IPI</a>
                                        @endif
                                    @endif
                                @endif
                                </div>
                            </div>
                            <div class='diff-text'></div>
                        </div>
                        @endif
                    </div>
                    
                    @include('blocks.like')
                    @include('blocks.bookmark')
                    @include('blocks.notincommunity')
                </div>

                @if (auth('web')->check())
                    @include('blocks.invite_response')
                @endif
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mt-2">Supporters</h5>
                    </div>                
                    <div class="card-body">
                        @foreach($idea->liked_users->sortByDesc('created_at')->take(92) as $u)
                        <a class="mr-2" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                            {{ $u->active_search_name }} 
                            </a>
                        @endforeach
                    </div>
                </div>
                @if(!is_egora())
                <div class="card">
                    <div class="card-header">
                        <h5 class="mt-2">Moderators</h5>
                    </div>                
                    <div class="card-body">
                        @foreach($idea->moderators->sortByDesc('created_at')->take(92) as $u)
                        <a class="mr-2" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                            {{ $u->active_search_name }} 
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif                

                <div class="card">
                    @if (auth('web')->check())
                        @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('invite_response', $notification) )
                        <div class="card-header">
                            <h5 class="mt-2">Comments</h5>
                        </div>

                        @else
                        <div class="card-header">
                            <div id="updates-tabs">
                                <div id="tabs" class="nav justify-content-center pb-0" data-tabs="tabs">
                                    <a style="font-size: large;" class="btn btn-primary col-12 col-md-4 m-1 @if (!request()->has('comments')) active @endif" href="#inviteTab" data-toggle="tab">Invitations</a>
                                    <a style="font-size: large;" class="btn btn-primary col-12 col-md-4 m-1 @if (request()->has('comments')) active @endif" href="#mainTab" data-toggle="tab">Comments</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card-body">
                            <div id="my-tab-content" class="tab-content">
                                @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('invite_response', $notification) )
                                    <div class="tab-pane active" id="mainTab">
                                    @include('blocks.comments', ['item'=>$idea, 'comments'=>$comments])
                                    </div>
                                @else
                                    <div class="tab-pane @if (request()->has('comments')) active @endif" id="mainTab">
                                    @include('blocks.comments', ['item'=>$idea, 'comments'=>$comments])
                                    </div>
                                    <div class="tab-pane @if (!request()->has('comments')) active @endif" id="inviteTab">
                                    @include('blocks.invite_examine')
                                    </div>
                                @endif
                            </div>

                        </div>                    
                    @else
                        <div class="card-header">
                            <div id="updates-tabs" class="nav justify-content-center pb-0">
                                <a style="font-size: large;" class="btn btn-primary col-12 col-md-4 m-1" href="{{ route('register') }}">Comments</a>
                            </div>
                        </div>
                    @endif 

                    @if( \Route::currentRouteName() == 'ideas.preview' )
                    <div class="card-body text-center">
                        <div class="col-10 offset-1">
                        Egora, “The Worldwide Stock-Market of Ideas”, enables everyone to<br/>
                        – develop their own political philosophy out of various ideas,<br/>
                        – determine which ideas are most strongly supported by the people, and<br/>
                        – find the true representatives of the public will, to elect them into public office.
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <a class='btn btn-primary btn-xl ml-2' href="{{ route('register') }}">{{__('Register Now')}}</a>
                    </div>
                    @endif
                    
                </div>
                
                @if (auth('web')->check())
                <div class="row col-md-3 mt-3">
                    <!--<a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>-->
                </div>
                @endif
                
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

