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
                                @if (is_egora())
                                <h3>{{ __('views.Ideological Profile') }}</h3>
                                @elseif (is_egora('community'))
                                <h3>{{ __('Community Matters') }}</h3>
                                @endif
                            @else 
                                <h3>{{ (App\SearchName::where('hash', app('router')->getRoutes()->match(app('request')->create(url()->previous()))->parameters()['hash'])->firstOrFail() ?? App\User::findOrFail($idea->user->id)->active_search_names->first())->name ?? '' }} </h3>
                            @endif
                        @else
                            @if (is_egora())
                            <h3>{{ __('views.Ideological Profile') }}</h3>
                            @elseif (is_egora('community'))
                            <h3>{{ __('Community Matters') }}</h3>
                            @endif
                        @endif 
                        <h3>Idea: Open</h3>
                    @else 
                        @if( \Route::currentRouteName() == 'ideas.preview' )
                            @if (is_egora())
                            <h3>{{ __('views.Ideological Profile') }}</h3>
                            @elseif (is_egora('community'))
                            <h3>{{ __('Community Matters') }}</h3>
                            @endif
                            <h3>Idea: Preview</h3>
                        @endif
                    @endif
                </div>
                
                @if (auth('web')->check())
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <!--<a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>-->
                    </div>
                    <div class="offset-6 col-md-3 mb-3">
                        <a href="{{ route('ideas.copy', $idea) }}" class='btn btn-primary btn-sm btn-block'>{{__('some.Copy and Edit')}}</a>
                    </div>
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                            Relevance: &nbsp;&nbsp;&nbsp;
                            @if (is_egora())
                                @if (isset($idea->nation))
                                {{ $idea->nation->title }} 
                                @endif
                            @elseif (is_egora('community'))
                                {{ $idea->community->title }}                                                        
                            @elseif (is_egora('municipal'))
                                {{ $idea->municipality->title }}                             
                            @endif
                            </div>
                            
                            @if (auth('web')->check())
                            @if(isset($current_idea_position) && !is_null($current_idea_position))
                            <div class="col-md-2">
                                <a class='btn btn-primary btn-sm ml-2' href="{{ route('ideas.unlike', $idea->id) }}">{{__('some.Remove and Close')}}</a>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                    {!! make_clickable_links(nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), htmlspecialchars($idea->content)))) !!}
                    </div>
                    
                    @include('blocks.like')
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mt-2">Supporters</h5>
                    </div>                
                    <div class="card-body">
                        @foreach($idea->liked_users_visible->sortByDesc('created_at')->take(92) as $user)
                        <a class="mr-2" href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">
                            {{ $user->active_search_names->first() ? $user->active_search_names->first()->name : '-'}} 
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                    </div>                
                
                    @if (auth('web')->check())
                    <div class="card-body">
                        @include('blocks.invite_examine')
                    </div>
                    
                    <div class="card-body">
                        @include('blocks.invite_response')
                    </div>
                    @endif

                    @if( \Route::currentRouteName() == 'ideas.preview' )
                    <div class="card-body text-center">
                        <div class="col-10 offset-1">
                        Egora, “The Worldwide Stock-Market of Ideas”, enables everyone to<br/>
                        – develop their own political philosophy out of various ideas,<br/>
                        – determine which ideas are most strongly supported by the people, and<br/>
                        – find the true representatives of the public will, to elect them into public office.
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <a class='btn btn-primary btn-xl ml-2' href="{{ route('index') }}">{{__('Register Now')}}</a>
                    </div>
                    @endif
                    
                </div>
                
                @if (auth('web')->check())
                <div class="row col-md-3 mt-3">
                    <!--<a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>-->
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

