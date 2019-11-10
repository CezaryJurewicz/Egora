@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.User') }}</h3>
                <div>
                    <div>User: {{ $user->name }}</div>
                    <div>Email: {{ $user->email }}</div>
                    <div>Search Name: @if(null !== $user->active_search_names->first()) {{ $user->active_search_names->first()->name }} @else - @endif
                    <a class="btn btn-sm btn-warning" href="{{ route('search_names.create') }}">Create</a>
                    <!--<a class="btn btn-sm btn-warning" href="{{ route('search_names.edit', $user->id) }}">Edit</a>-->
                    </div>
                    <div>Nation: {{ $user->nation->title }}</div>
                    <div>Ideas:</div>
                    @foreach($user->ideas as $idea)
                    <div>
                        <a href="{{ route('ideas.view', $idea->id) }}">#{{$idea->id}} {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...</a>
                    </div>
                    @endforeach
                </div>
                
                <a class="btn btn-sm btn-primary" href="{{  url()->previous() }}">Back</a>
                <a class="btn btn-sm btn-warning" href="{{ route('users.edit', $user->id) }}">Edit</a>

            </div>
        </div>
    </div>
    </div>
</div>
@endsection

