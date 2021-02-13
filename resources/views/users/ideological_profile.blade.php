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
                            <div class="row">
                                <div class="col col-md-8 offset-2">
                                    <div class="text-center">
                                        @if (is_egora())
                                        <h3>{{ __('views.Ideological Profile') }}</h3>
                                        @elseif (is_egora('community'))
                                        <h3>{{ __('Community Matters') }}</h3>
                                        @endif
                                    </div>
                                </div>
                                <div class="col col-md-2 text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ route('users.about', $user->active_search_names->first()->hash) }}">About Me</a>
                                </div>
                            </div>
                            <div>
                                @if (is_egora())
                                <div class="accordion mb-3" id="accordion">
                                    <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                                      <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Guide
                                          </button>
                                        </h2>
                                      </div>

                                      <div id="collapseOne" class="collapse show1" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            <p>The Ideological Profile (IP) is a person’s existential, political, economic,
                                            social, and personal philosophy. The 23-point idea is the most fundamental
                                            idea or the most practically important idea, depending on which strategy a
                                            person uses for building their IP. The total number of points [verified] users
                                            of Egora assign to a particular idea in their IPs is given as “IDI Points”.</p>
                                            <p>The 0-point ideas are a convenient way to suggest alternative ideas to the
                                            point-weighted ideas. The 0-point ideas are also used by ILP Members to
                                            control Egora and to organize of the ILP.</p>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                                <div class="mb-1 text-right"> 
                                    @if (is_egora())
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.create') }}">Create New Idea</a>
                                    @elseif (is_egora('community'))
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.create', ['community_id'=>$community_id]) }}">Create New Idea</a>
                                    @endif
                                </div>
                                @endif
                                @if($user->liked_ideas->isNotEmpty())
                                <div class="card p-2">
                                    @foreach($user->liked_ideas as $idea)
                                    <div class="card mb-3">
                                        <a id="idea{{$idea->id}}" style="display: block; position: relative;top: -70px;visibility: hidden;"></a>
                                        <div class="card-header">
                                            <div class="row">
                                                @if (auth('web')->check() && auth('web')->user()->can('move', [$idea, $user]) )
                                                <div class="col-1">
                                                    @php
                                                        list($up, $down) = ip_has_place($user->liked_ideas, $idea)
                                                    @endphp
                                                    @if ($up)
                                                    <a href="{{ route('ideas.move', [$idea->id, $user->id, 'd'=>1]) }}" class="mb-1 btn-outline-secondary btn-sm rounded-circle">
                                                    <i class="fa fa-chevron-up pt-1"></i>
                                                    </a><br/>
                                                    @else
                                                    &nbsp;<br/>
                                                    @endif
                                                    @if ($down)
                                                    <a href="{{ route('ideas.move', [$idea->id, $user->id, 'd'=>-1]) }}" class="btn-outline-secondary btn-sm rounded-circle                     ">
                                                    <i class="fa fa-chevron-down pt-1"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                                <div class="col-8 col-sm-4">
                                                @else
                                                <div class="col-9 col-sm-5">
                                                @endif
                                                    <b>@if ($idea->pivot->position>0) {{$idea->pivot->position}} @else 0 ({{$idea->pivot->order}}) @endif</b>
                                                    <br/>
                                                @if ($idea->nation)
                                                    {{$idea->nation->title}}
                                                @elseif ($idea->community)
                                                    {{$idea->community->title}}
                                                @endif
                                                </div>
                                                <div class="col-2 text-center">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                                </div>
                                                <div class="offset-sm-1 col-12 col-sm-4">
                                                <div class="row">
                                                    <div class="col-6">
                                                        IDI Points:
                                                    </div>
                                                    <div class="col-6">
                                                    {{ number_format( $idea->liked_users->pluck('pivot.position')->sum() ) }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                Supporters:
                                                    </div>
                                                    <div class="col-6">
                                                {{ number_format($idea->liked_users->count()) }}
                                                @include('blocks.debug.users',['users' => $idea->liked_users])
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {!! make_clickable_links(shorten_text($idea->content)) !!}
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
@endsection

