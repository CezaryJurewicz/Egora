@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Idea') }} {{__('for')}} {{ $idea->nation->title }} </h3>
                <div class="card">
                    <div class="card-header">
                        By: <a href="{{ route('users.ideological_profile', $idea->user->id) }}">
                            {{ $idea->user->active_search_names->first()? $idea->user->active_search_names->first()->name : '-'}}
                        </a>
                    </div>
                    <div class="card-body">
                    {{ $idea->content }}
                    </div>
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

