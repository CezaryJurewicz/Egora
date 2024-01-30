@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            @include('blocks.ip_left')                 
                        </div>
                        
                        <div class="col-md-9">
                            <div class="row pb-3">
                                <div class="col-12 col-md-3">
                                    @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                                        @if(!isset($external))
                                        <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.bookmarked_ideas') }}">Bookmarks</a>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="text-center">
                                        <h3>{{ __('views.About Me') }}</h3>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 text-right">
                                    @if (isset($external))                              
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.external_ip', _clean_search_name($user->active_search_names->first()->name)) }}">IP</a>
                                    @else
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.ideological_profile', $user->active_search_names->first()->hash) }}">IP</a>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="card">
                                    <div class="card-body" style="min-height: 300px;">
                                            {!! filter_text($user->about_me) !!}                                                                    
                                    </div>
                                </div>
                            </div>
                            @if (auth('web')->check() && auth('web')->user()->can('about_edit', [App\User::class, $user->active_search_names->first()->hash]))
                            <div class="row">
                                <div class="col-12 col-md-2 text-right offset-md-10 p-3">
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.about_edit', $user->active_search_names->first()->hash) }}">Edit</a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row">
                                <div class="col col-md-8 offset-md-2 mt-3">
                                    <div class="text-center">
                                        <h3>{{ __('Status') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="card">
                                    <div class="card-body">
                                        @include('blocks.statuses', ['item'=> $user, 'statuses'=> $comments])
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection

