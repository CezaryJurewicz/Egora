@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Idea') }}</h3>
                <div>
                    <div>Nation: {{ $idea->nation->title }}</div>
                    <div>User: <a href="{{ route('users.view', $idea->user->id) }}">{{ $idea->user->name }}</a> (aka '{{ $idea->user->active_search_names->first()? $idea->user->active_search_names->first()->name : '-'}}')</div>
                    <div>Position: {{ $idea->position }}</div>
                    <div>Content: {{ $idea->content }}</div>
                </div>
                
                <div class="row mt-2">
                    <form class="col-md-1" action="{{ url()->previous() }}" method="GET">
                        <button type='submit' class='btn btn-primary'>{{__('Back')}}</button>
                    </form>
                
                    @include('blocks.like')
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

