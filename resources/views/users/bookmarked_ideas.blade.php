@extends('layouts.app')

@section('content')
<div class="container-lg">
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
                                <div class="col-12 col-md-2 col-lg-2 p-md-0">
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.ideological_profile', [$user->active_search_names->first()->hash, 'community_id'=>$community_id]) }}">IP</a>
                                </div>
                                <div class="col-10 col-lg-6 col-md-6 p-0 offset-1 offset-md-1 offset-lg-1">
                                    <div class="text-center">
                                        <h3>{{ __('views.Bookmarked Ideas') }}</h3>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 col-lg-2 text-right p-md-0 offset-md-1 offset-lg-1">
                                    @if (is_egora())
                                    <a class="btn btn-sm btn-primary btn-block mb-1" href="{{ route('users.about', $user->active_search_names->first()->hash) }}">About Me</a>
                                    @endif
                                </div>
                            </div>                            
                            
                            <div class="row">
                                <div class="col-12 p-md-0">
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
                                                    <div class="col-10 col-sm-3 col-md-4">
                                                    @else
                                                    <div class="col-11 col-sm-4 col-md-5">
                                                    @endif
                                                        <b>{{$idea->pivot->order}}</b>
                                                        <br/>
                                                    @if ($idea->nation)
                                                        {{$idea->nation->title}}
                                                    @elseif ($idea->community)
                                                        {{$idea->community->title}}
                                                    @endif
                                                        <br/>
                                                        Views: {{$idea->views_cnt}}
                                                    </div>
                                                    <div class="col-12 col-sm-4 col-md-2 pr-sm-4 pl-sm-4 pr-md-0 pl-md-0 text-center small">
                                                        <a class="btn btn-sm btn-primary col-12" href="{{ route('ideas.view', [$idea->id,'cnt']) }}">{{ __('Open') }}</a>
                                                        <br/>
                                                        <a class="col-12 p-0" href="{{ route('ideas.view', [$idea->id, 'comments']).'#tabs' }}">{{ __('Comments:').' '.($idea->comments->count() + $idea->comments->reduce(function ($count, $comment) { return $count + $comment->comments->count(); }, 0)) }}</a>
                                                        <br/>
                                                        <small>
                                                            Votes: {{$idea->approval_ratings->count()}}
                                                        </small>                                                             
                                                    </div>
                                                    <div class="col-12 col-sm-4 offset-md-1">
                                                        @if (is_egora())
                                                        <div class="row">
                                                            <div class="col-8">
                                                                IDI Points:
                                                            </div>
                                                            <div class="col-4 p-0">
                                                            {{ number_format( $idea->liked_users->pluck('pivot.position')->sum() ) }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-8">
                                                        Supporters:
                                                            </div>
                                                            <div class="col-4 p-0">
                                                        @if (is_egora())
                                                        {{ number_format($idea->liked_users->count()) }}
                                                        @else
                                                        {{ number_format($idea->liked_users->count() - $idea->moderators->count()) }}
                                                        @endif
                                                            </div>
                                                        </div>
                                                        @if (!is_egora())
                                                        <br/>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-8">
                                                                Rating:
                                                            </div>
                                                            <div class="col-4 p-0">
                                                                {{ number_format($idea->approval_ratings()->avg('score'), 3) }}
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

