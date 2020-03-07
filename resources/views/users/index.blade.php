@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Users') }}</h3>
                
                <form action="{{ route('users.index') }}" method="POST">
                <div class="form-group row">
                    <label for="search" class="col-md-2 col-form-label">{{ __('Search') }} <br/> (id, name, email)</label>

                        @csrf
                        <div class="col-md-6">
                            <input id="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search') ?: $search }}" autofocus required>

                            @error('search')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-1">
                            <div class="input-group">
                                <button type='submit' class='btn btn-sm btn-primary'>{{__('some.Search')}}</button>
                            </div>
                        </div>
                </div>
                </form>
                
                <div>   
                    <a href="{{ route('users.index',['awaiting']) }}">List Awaiting Verification Users</a>                
                </div>
                
                <div class="mt-3">
                    @if($users->isNotEmpty())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Info')}}</th>
                                    <!--<th scope="col">{{ __('tables.Name')}}</th>-->
                                    <!--<th scope="col">{{ __('tables.Email')}}</th>-->
                                    <th scope="col">{{ __('tables.Ideas')}}</th>
                                    <th scope="col">{{ __('tables.Created')}}</th>
                                    <!--<th scope="col">{{ __('tables.Updated')}}</th>-->
                                    <th scope="col">{{ __('tables.Last Online At')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($users as $i=>$user)
                                <tr>
                                    <th scope="row">{{$user->id}}</th>
                                    <td>
                                        {{ __('user.Name')}}: {{ $user->name }} 
                                        <br>
                                        {{ __('user.Search Name')}}: {{ $user->active_search_names->first() ? $user->active_search_names->first()->name : '-'}} 
                                        <br>
                                        {{ __('user.Email')}}: {{ $user->email }}
                                        <br>
                                        {{ __('user.Nation')}}: {{ $user->nation->title }}                                        
                                        <br>
                                        {{ __('user.User Class')}}: {{$user->user_type->fake_text}} {{$user->user_type->former_text}} {{ $user->user_type->class }} {{$user->user_type->candidate_text}}
                                            ({{$user->user_type->verified_text}})
                                    </td>
                                    <td>
                                        @isset($user->ideas)
                                        {{ count($user->ideas) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $user->createdDate() }}</td>
                                    <!--<td>{{ $user->updatedDate() }}</td>-->
                                    <td>{{ $user->lastOnlineAtDate() }}</td>
                                    <td>
                                        @if (!$user->trashed())
                                        <a class="btn btn-sm btn-primary" href="{{ route('users.profile', $user->id) }}">@lang('some.View')</a>
                                        @endif
                                        @if ($user->trashed())
                                        <form action="{{ route('users.restore',[$user->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-warning'>{{__('some.Restore')}}</button>
                                            </div>
                                        </form>
                                        @else
                                        <form action="{{ route('users.deactivate',[$user->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-secondary'>{{__('Deactivate')}}</button>
                                            </div>
                                        </form>
                                        @endif
                                        <form action="{{ route('users.delete',[$user->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('user.No users')</p>
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

