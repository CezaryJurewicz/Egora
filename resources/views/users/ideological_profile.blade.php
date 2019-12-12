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
                            
                            <div class="mt-2">{{ $user->user_type->subtitle }}</div>
                            <!--<div class="mt-2">{{ $user->user_type->title }}</div>-->
                            
                            <div class="mt-2"><h3>{{ $user->name }}</h3></div>
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
                                <a class="btn btn-ssm btn-secondary col-md-3" href="{{ route('search_names.edit', $user->active_search_names->first()->id) }}">Edit</a>
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

                            <div class="mt-3">
                            @include('blocks.verification')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.ilp')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.petition')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.follow')
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
                                            <div class="row small">
                                                <div class="col-md-1"><b>#{{$idea->pivot->position}}</b></div>
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

