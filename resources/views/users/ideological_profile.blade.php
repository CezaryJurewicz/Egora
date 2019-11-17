@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <h3>{{ __('views.Ideological Profile') }}</h3>
                    <div class="row">
                        <div class="col-md-3">
                            @include('blocks.user_image')                 
                            
                            <div>{{ $user->user_type->title }}</div>
                            <div>{{ $user->name }}</div>
                            <div>Search Name: @if(null !== $user->active_search_names->first()) {{ $user->active_search_names->first()->name }} @else - @endif</div>
                            <div>{{ $user->email }}</div>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">Ideas: <a class="btn btn-sm btn-primary" href="{{ route('ideas.create') }}">Create Idea</a></div>
                                <div class="card-body">
                                    @foreach($user->ideas as $idea)
                                    <div>
                                        #{{$idea->position}} {{$idea->nation->title}} 
                                        <a href="{{ route('ideas.view', $idea->id) }}">{{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...</a>

                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @if($user->liked_ideas->isNotEmpty())
                            <div class="card mt-2">
                                <div class="card-header">Liked Ideas:</div>
                                <div class="card-body">
                                    @foreach($user->liked_ideas as $idea)
                                    <div>
                                        #{{$idea->pivot->position}} {{$idea->nation->title}} 
                                        <a href="{{ route('ideas.view', $idea->id) }}">{{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...</a>

                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

