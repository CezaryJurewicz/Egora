@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            @include('blocks.user_image')                 
                            
                            <div class="mt-3"><h4><i>{{ $user->user_type->subtitle }}</i></h4></div>
                            <!--<div class="mt-2">{{ $user->user_type->title }}</div>-->
                            
                            <div class="mt-2"><h4>@if (auth('admin')->check()) #{{ $user->id }}: @endif {{ $user->name }}</h4></div>
                            @if (auth('web')->check() && auth('web')->user()->can('update', $user))
                            <form action="{{ route('users.update_name', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                <button disabled class="btn btn-ssm btn-secondary col-md-3">Edit</button>
                            </form>
                            @endif
                            
                            <div class="mt-2">
                                @if(null !== $user->active_search_names->first()) <b>{{ $user->active_search_names->first()->name }}</b> @else - @endif
                                <br>
                                @if (auth('web')->user() && $user->id == auth('web')->user()->id && $user->active_search_names->first())
                                <!--<a class="btn btn-ssm btn-secondary col-md-3" href="{{ route('search_names.edit', $user->active_search_names->first()->id) }}">Edit</a>-->
                                @endif
                            </div>

                            <div class="mt-2">{{ $user->nation->title }}</div>
                            @if (auth('web')->check() && auth('web')->user()->can('update', $user))
                            <form action="{{ route('users.update_nation', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                <button disabled class="btn btn-ssm btn-secondary col-md-3">Edit</button>
                            </form>
                            @endif
                            
                            <div class="mt-2">{{ $user->contacts }}</div>
                            @if (auth('web')->check() && auth('web')->user()->can('update', $user))
                            <form action="{{ route('users.update_contacts', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                <button disabled class="btn btn-ssm btn-secondary col-md-3"">Edit</button>
                            </form>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('update', $user))
                            <div class="mt-2">
                                <a class="btn btn-sm btn-secondary btn-block" href="{{ route('users.edit', $user->id) }}">Edit</a>
                            </div>
                            @endif

                            <div class="mt-2">
                            @include('blocks.ilp')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.petition')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.follow')
                            </div>

                            <div class="mt-3">
                            @include('blocks.verification')
                            </div>
                            
                            <div class="mt-2">
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('disqualify_membership', $user) )
                                <a class="btn btn-black btn-sm btn-block" href="{{ route('users.disqualify_membership', $user->id ) }}">Disqualify Membership</a>
                                @endif
                            </div>
                            
                            <div class="mt-2">
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('cancel_guardianship', $user) )
                                <a class="btn btn-black btn-sm btn-block" href="{{ route('users.cancel_guardianship', $user->id ) }}">Cancel Guardianship</a>
                                @endif
                            </div>
                            
                            <div class="mt-2">
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('allow_guardianship', $user) )
                                <a class="btn btn-black btn-sm btn-block" href="{{ route('users.allow_guardianship', $user->id ) }}">Allow Guardianship</a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-9">
                            <div class="text-center">
                            <h3>{{ __('views.Ideological Profile') }}</h3>
                            </div>
                            <div>
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
                                            <p>The Ideological Profile is your existential, political, economic, social, and even personal philosophy. The 23-point idea is your most fundamental idea or most practically important idea. The 0-point ideas are a convenient way to organize your alternative ideas to the numbered ideas. The 0-point ideas are also used by ILP Members to support Egora related ideas.</p>
                                            <p>If you ever need more good ideas, you will always find them in the Ideological Profiles of your friends and on either of the Indexes. But to learn about those ideas in detail, there is no better opportunity than the ILP meetings.</p>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                
                                @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                                <div class="mb-1 text-right"> 
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.create') }}">Create New Idea</a>
                                </div>
                                @endif
                                @if($user->liked_ideas->isNotEmpty())
                                <div class="card p-2">
                                    @foreach($user->liked_ideas as $idea)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-1 pr-0"><b>@if ($idea->pivot->position>0) {{$idea->pivot->position}} @else 0 ({{$idea->pivot->order}}) @endif</b></div>
                                                <div class="col-md-4">{{$idea->nation->title}} </div>
                                                <div class="col-md-2 text-center">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                                </div>
                                                <div class="offset-1 col-md-4">
                                                IDI Points: {{ number_format( $idea->liked_users->pluck('pivot.position')->sum() ) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {!! shorten_text($idea->content) !!}
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

