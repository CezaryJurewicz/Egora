@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="text-center">
                    
                    @if (app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'ideas.indexes')
                        <h3>{{ __('views.Idea Dominance Index') }}</h3>
                    @elseif (app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'ideas.popularity_indexes')
                        <h3>{{ __('views.Idea Popularity Index') }}</h3>
                    @elseif ( auth('web')->check()
                        && app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'users.ideological_profile'
                        && app('router')->getRoutes()->match(app('request')->create(url()->previous()))->parameters()['user'] == auth('web')->user()->id )
                        <h3>{{ __('views.Ideological Profile') }}</h3>
                    @else 
                        <h3>{{ App\User::findOrFail(app('router')->getRoutes()->match(app('request')->create(url()->previous()))->parameters()['user'])->active_search_names->first()->name ?? '' }}
                        {{--<h3>{{ $idea->user->active_search_names->first()? $idea->user->active_search_names->first()->name : '-'}}</h3>--}}
                    @endif 
                    
                    <h3>Idea: Open</h3>
                </div>
                
                <div class="row col-md-3 mb-3">
                    <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                </div>
                <div class="card">
                    <div class="card-header row">
                        <div class="col-md-10">
                        Relevance: {{ $idea->nation->title }} 
                        </div>
                        
                        @if(!is_null($current_idea_position))
                        <div class="col-md-2">
                            <a class='btn btn-primary btn-sm ml-2' href="{{ route('ideas.unlike', $idea->id) }}">{{__('some.Remove and Close')}}</a>
                        </div>
                        @endif
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

