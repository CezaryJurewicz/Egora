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
                    <div>Nation: {{ $user->nation->title }}</div>
                    <div>Ideas:</div>
                    @foreach($user->ideas as $idea)
                    <div>
                        <a href="{{ route('ideas.view', $idea->id) }}">#{{$idea->id}} {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...</a>
                    </div>
                    @endforeach
                </div>
                
                <a class="btn btn-sm btn-primary" href="{{  url()->previous() }}">Back</a>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

