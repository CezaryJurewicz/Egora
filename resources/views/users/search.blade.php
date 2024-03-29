@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-8">
        @if ((auth('web')->user()?:auth('admin')->user())->can('searchAny', App\User::class))
        <div class="panel">
            <div class="panel-body">
                <div class="text-center"> 
                <h3>{{ __('views.Philosopher Search') }}</h3>
                </div>
                <form action="{{ route('users.search') }}" method="POST" autocomplete="off">
                    <div class="form-group row mt-4">
                        <label for="search_name" class="col-md-3 col-form-label">@lang('Search-Name') <br>@lang('(complete or partial)')</label>

                        @csrf
                        <div class="col-md-7">
                            <input id="search_name" type="text" class="form-control @error('search_name') is-invalid @enderror" name="search_name" value="{{ old('search_name') ?: $search_name }}" autofocus autocomplete="off">

                            @error('search_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    @if (is_egora())
                    <div class="form-group row">
                        <label for="nation" class="col-md-3 col-form-label">@lang('Nation')<br> @lang('(optional)')</label>

                        <div class="col-md-7">
                            <div id="NationSearch" value="{{  $nation }}"></div>

                            @error('nation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="followers" class="col-md-3 col-form-label">{{ __('My followers') }}</label>

                        <div class="col-md-1">
                            <input class="form-control-sm" id="followers" name="followers" value=1 type="checkbox" {{ (old('followers')?: $followers) ? ' checked' : '' }}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="officer" class="col-md-3 col-form-label">{{ __('ILP officers') }}</label>

                        <div class="col-md-1">
                            <input class="form-control-sm" id="officer" name="officer" value=1 type="checkbox" onClick="c = document.getElementById('officer_petitioner'); c.checked = false;" {{ (old('officer')?: $officer) ? ' checked' : '' }}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="officer_petitioner" class="col-md-3 col-form-label">{{ __('Officer-petitioners') }}</label>

                        <div class="col-md-1">
                            <input class="form-control-sm" id="officer_petitioner" name="officer_petitioner" value=1 type="checkbox" onClick="c = document.getElementById('officer'); c.checked = false;" {{ (old('officer_petitioner')?: $officer_petitioner) ? ' checked' : '' }} >
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type='submit' class='btn btn-sm btn-primary btn-static-200'>{{__('some.Search')}}</button>
                        </div>
                    </div>
                </form>
                    
                <div class="mt-5">
                    @if($users->isNotEmpty())
                        @if ($recent)
                        <center>
                        <h5>{{ __('views.New Philosophers') }}</h5>
                        </center>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('tables.Category')}}</th>
                                    <th scope="col">{{ __('tables.Search-Name')}}</th>
                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($users as $i=>$user)
                                <tr>
                                    <td>
                                        {{$user['user_type']['title']}}
                                    </td>
                                    <td>
                                        @if (auth('admin')->check())
                                        <a href="{{ route('users.profile', $user['id']) }}">
                                        {{ isset($user['active_search_names'][0]) ? $user['active_search_names'][0]['name'] : '-'}} 
                                        </a>
                                        @else
                                        <a href="{{ route('users.ideological_profile', $user['active_search_names'][0]['hash']) }}">
                                        {{ isset($user['active_search_names'][0]) ? $user['active_search_names'][0]['name'] : '-'}} 
                                        </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user['nation']['title'] }}                                        
                                    </td>
                                </tr>
                    @empty
                        <!--<p>@lang('user.No users found')</p>-->
                    @endforelse
                    
                    @if($users->isNotEmpty())                 
                            </tbody>
                        </table>
                        {{ $users->appends(request()->except('_token'))->links() }}
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
        
    <div class="col-md-4">
        @include('blocks.following')
    </div>
        
    </div>
</div>
@endsection

