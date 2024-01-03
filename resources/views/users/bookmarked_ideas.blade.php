@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            @include('blocks.ip_left')                 
                        </div>
                        
                        <div class="col-md-9">
                            <div class="row pb-3">
                                <div class="col-12 col-md-3">
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.ideological_profile', [$user->active_search_names->first()->hash, 'community_id'=>$community_id]) }}">IP</a>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="text-center">
                                        <h3>{{ __('views.Bookmarked Ideas') }}</h3>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 text-right">
                                    @if (is_egora())
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.about', $user->active_search_names->first()->hash) }}">About Me</a>
                                    @endif
                                </div>
                            </div>                            
                            
                            <div class="row">
                                <div class="col-12">
                                    @if($user->bookmarked_ideas->isNotEmpty())
                                    <div class="card p-2">
                                        @foreach($user->bookmarked_ideas as $idea)
                                        <div class="card mb-3">
                                            <a id="idea{{$idea->id}}" style="display: block; position: relative;top: -70px;visibility: hidden;"></a>
                                            <div class="card-header">
                                                <div class="row">
                                                    @if (auth('web')->check() && auth('web')->user()->can('bookmark_move', [$idea, $user]) )
                                                    <div class="col-1">
                                                        @php
                                                            list($up, $down) = bookmarks_has_place($user->bookmarked_ideas, $idea)
                                                        @endphp
                                                        @if ($down)
                                                        <a href="{{ route('ideas.bookmark_move', [$idea->id, $user->id, 'd'=>-1]) }}" class="mb-1 btn-outline-secondary btn-sm rounded-circle">
                                                        <i class="fa fa-chevron-up pt-1"></i>
                                                        </a><br/>
                                                        @else
                                                        &nbsp;<br/>
                                                        @endif
                                                        @if ($up)
                                                        <a href="{{ route('ideas.bookmark_move', [$idea->id, $user->id, 'd'=>1]) }}" class="btn-outline-secondary btn-sm rounded-circle                     ">
                                                        <i class="fa fa-chevron-down pt-1"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                    <div class="col-10 col-sm-4">
                                                    @else
                                                    <div class="col-11 col-sm-5">
                                                    @endif
                                                        <b>{{$idea->pivot->order}}</b>
                                                        <br/>
                                                    @if ($idea->nation)
                                                        {{$idea->nation->title}}
                                                    @elseif ($idea->community)
                                                        {{$idea->community->title}}
                                                    @endif
                                                    </div>
                                                    <div class="col-12 col-sm-2 pr-0 pl-0 text-center small">
                                                        <a class="btn btn-sm btn-primary col-12" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                                        <br/>
                                                        <a class="col-12" href="{{ route('ideas.view', [$idea->id, 'comments']).'#tabs' }}">{{ __('Comments:').' '.($idea->comments->count() + $idea->comments->reduce(function ($count, $comment) { return $count + $comment->comments->count(); }, 0)) }}</a>
                                                    </div>
                                                    <div class="offset-sm-1 col-12 col-sm-4">
                                                        @if (is_egora())
                                                        <div class="row">
                                                            <div class="col-6">
                                                                IDI Points:
                                                            </div>
                                                            <div class="col-6">
                                                            {{ number_format( $idea->liked_users->pluck('pivot.position')->sum() ) }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-6">
                                                        Supporters:
                                                            </div>
                                                            <div class="col-6">
                                                        @if (is_egora())
                                                        {{ number_format($idea->liked_users->count()) }}
                                                        @else
                                                        {{ number_format($idea->liked_users->count() - $idea->moderators->count()) }}
                                                        @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                {!! shorten_text_link($idea->content) !!}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        

                    </div>
                        
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

