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
                        @if (is_egora())
                        <form id="copy" method="POST" action="{{ route('ideas.copy', $idea) }}">
                        @csrf
                        <button class='btn btn-primary btn-sm btn-block'>{{__('some.Copy and Edit')}}</button>
                        </form>
                        @endif
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
                            @endif
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
                    
                    <!-- temp -->
                    @if(is_egora())
                    <div class="card-body">
                        @include('blocks.like')
                    </div>
                    @endif                
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mt-2">Recent supporters</h5>
                    </div>                
                    <div class="card-body">
                        @foreach($idea->liked_users->sortByDesc('created_at')->take(92) as $user)
                        <a class="mr-2" href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">
                            {{ $user->active_search_names->first() ? $user->active_search_names->first()->name : '-'}} 
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                    </div>                
                
                    <div class="card-body">
                        @include('blocks.invite_examine')
                    </div>
                    
                    <div class="card-body">
                        @include('blocks.invite_response')
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

