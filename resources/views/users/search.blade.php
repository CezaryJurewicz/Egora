@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Egora User Search') }}</h3>
                <form action="{{ route('users.search') }}" method="POST">
                    <div class="form-group row mt-4">
                        <label for="search_name" class="col-md-2 col-form-label">{{ __('"Search Name" (complete or partial)') }}</label>

                        @csrf
                        <div class="col-md-7">
                            <input id="search_name" type="text" class="form-control @error('search_name') is-invalid @enderror" name="search_name" value="{{ old('search_name') ?: $search_name }}" autofocus required>

                            @error('search_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nation" class="col-md-2 col-form-label">{{ __('Nation (optional)') }}</label>

                        @csrf
                        <div class="col-md-7">
                            <div id="NationSearch" value="{{  $nation }}"></div>

                            @error('nation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="offset-2 col-md-3">
                            <div class="input-group">
                                <button type='submit' class='btn btn-sm btn-primary'>{{__('some.Search')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div>
                    @if($users->isNotEmpty())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('tables.Class')}}</th>
                                    <th scope="col">{{ __('tables.Search Name')}}</th>
                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                    <th scope="col">{{ __('tables.Ideas')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($users as $i=>$user)
                                <tr>
                                    <td>
                                        {{$user->user_type->title}}
                                    </td>
                                    <td>
                                        <a href="{{ route('users.ideological_profile', $user->id) }}">
                                        {{ $user->active_search_names->first() ? $user->active_search_names->first()->name : '-'}} 
                                        </a>
                                    </td>
                                    <td>
                                        {{ $user->nation->title }}                                        
                                    </td>
                                    <td>
                                        @isset($user->ideas)
                                        {{ count($user->ideas) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('user.No users found')</p>
                    @endforelse
                    
                    @if($users->isNotEmpty())                 
                            </tbody>
                        </table>
                    
                        {{ $users->appends(request()->except('_token'))->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

