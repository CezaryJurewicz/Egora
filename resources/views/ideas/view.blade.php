@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="text-center">
                    
                    @if (auth('web')->check())
                        @if (app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'ideas.indexes')
                            <h3>{{ __('views.Idea Dominance Index') }}</h3>
                        @elseif (app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'ideas.popularity_indexes')
                            <h3>{{ __('views.Idea Popularity Index') }}</h3>
                        @elseif ( auth('web')->check() && app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'users.ideological_profile')
                            @if (app('router')->getRoutes()->match(app('request')->create(url()->previous()))->parameters()['hash'] == auth('web')->user()->active_search_names->first()->hash )
                                <h3>{{ __('views.Ideological Profile') }}</h3>
                            @else 
                                <h3>{{ (App\SearchName::where('hash', app('router')->getRoutes()->match(app('request')->create(url()->previous()))->parameters()['hash'])->firstOrFail() ?? App\User::findOrFail($idea->user->id)->active_search_names->first())->name ?? '' }} </h3>
                            @endif
                        @endif 
                    @endif
                    <h3>Idea: Open</h3>
                </div>
                
                @if (auth('web')->check())
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                    </div>
                    <div class="offset-6 col-md-3 mb-3">
                        <form id="copy" method="POST" action="{{ route('ideas.copy', $idea) }}">
                        @csrf


                        
                        <button class='btn btn-primary btn-sm btn-block'>{{__('some.Copy and Edit')}}</button>
                        </form>
                    </div>
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                            Relevance: &nbsp;&nbsp;&nbsp;{{ $idea->nation->title }} 
                            </div>

                            @if(!is_null($current_idea_position))
                            <div class="col-md-2">
                                <a class='btn btn-primary btn-sm ml-2' href="{{ route('ideas.unlike', $idea->id) }}">{{__('some.Remove and Close')}}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                    {!! make_clickable_links(strip_tags(nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), $idea->content)), '<br><p><b><i><li><ul><ol>')) !!}
                    </div>
                    <div class="card-footer pt-4 pb-4">
                        @if(!is_null($current_idea_point_position))Current Position in my IP:<span  class="font-weight-bold">&nbsp;&nbsp;&nbsp;{{ str_pad($current_idea_point_position, 20, ' ', STR_PAD_LEFT) }}</span> @endif
                    </div>
                    <div class="card-body">
                        @include('blocks.like')
                    </div>
                
                </div>
                
                @if (auth('web')->check())
                <div class="row col-md-3 mt-3">
                    <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

