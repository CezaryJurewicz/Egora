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
                            
                            <div class="mt-2">{{ $user->user_type->title }}</div>
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('update', $user))
                            <div class="mt-2">{{ $user->name }}</div>
                            @endif
                            
                            <div class="mt-2">
                                @if(null !== $user->active_search_names->first()) {{ $user->active_search_names->first()->name }} @else - @endif
                                
                                @if (auth('web')->user() && $user->id == auth('web')->user()->id && $user->active_search_names->first())
                                <a class="btn btn-sm btn-secondary" href="{{ route('search_names.edit', $user->active_search_names->first()->id) }}">Edit</a>
                                @endif
                            </div>
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('update', $user))
                            <div class="mt-2">{{ $user->email }}</div>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('update', $user))
                            <div class="mt-2">
                                <a class="btn btn-sm btn-secondary btn-block" href="{{ route('users.edit', $user->id) }}">Edit</a>
                            </div>
                            @endif

                            @if ((auth('web')->user()?:auth('admin')->user())->can('delete', $user))
                            <div class="mt-2">
                                <form action="{{ route('users.delete',$user->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE"/>
                                    <div class="input-group">
                                        <button type='submit' class='btn btn-sm btn-danger btn-block'>{{__('some.Delete')}}</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                            
                            <div class="mt-3">
                            @include('blocks.verification')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.ilp')
                            </div>
                            <div class="mt-2">
                            @include('blocks.follow')
                            </div>
                        </div>
                        
                        <div class="col-md-9">
                            <h3>{{ __('views.Ideological Profile') }}</h3>
                            <div>
                                <div class="mb-1">Ideas: 
                                    @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.create') }}">Create Idea</a>
                                    @endif
                                </div>
                                @if($user->liked_ideas->isNotEmpty())
                                <div class="card p-2">
                                    @foreach($user->liked_ideas as $idea)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-1">{{$idea->pivot->position}}</div>
                                                <div class="col-md-2">{{$idea->nation->title}} </div>
                                                <div class="col-md-2">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                                </div>
                                                <div class="offset-3 col-md-2">
                                                IDI Points {{ $idea->liked_users->pluck('pivot.position')->sum() }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 50)) }} ...
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

