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
                            <div class="row">
                                <div class="col-12 col-md-8 offset-md-2">
                                    <div class="text-center">
                                        <h3>{{ __('views.About Me') }}</h3>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 text-right">
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.ideological_profile', $user->active_search_names->first()->hash) }}">IP</a>
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
                                <div class="col col-md-2 text-right offset-10 p-3">
                                    <a class="btn btn-sm btn-primary" href="{{ route('users.about_edit', $user->active_search_names->first()->hash) }}">Edit</a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row">
                                <div class="col col-md-8 offset-2 mt-3">
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

