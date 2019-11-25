@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="text-center">
                    <h3>{{ $idea->user->active_search_names->first()? $idea->user->active_search_names->first()->name : '-'}}</h3>
                </div>
                
                <div class="row col-md-3 mb-3">
                    <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                </div>
                <div class="card">
                    <div class="card-header">
                        Relevance: {{ $idea->nation->title }} 
                    </div>
                    <div class="card-body">
                    {{ $idea->content }}
                    </div>
                    <div class="card-footer">
                        Current Point Position in my IP: {{ $current_idea_position }}
                    </div>
                <div class="mt-2 mb-2">
                    @include('blocks.like')
                </div>
                
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-3 offset-4">
                        <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

