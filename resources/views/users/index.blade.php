@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Users') }}</h3>
                <div>
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
                                    <th scope="col">{{ __('tables.Updated')}}</th>
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
                                        {{ __('user.User Class')}}: {{$user->user_type->fake_text}} {{ $user->user_type->class }} {{$user->user_type->candidate_text}}
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
                                    <td>{{ $user->updatedDate() }}</td>
                                    <td>
                                        @if (!$user->trashed())
                                        <a class="btn btn-sm btn-primary" href="{{ route('users.view', $user->id) }}">@lang('some.View')</a>
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
                                        <form action="{{ route('users.delete',[$user->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('users.No users')</p>
                    @endforelse
                    
                    @if($users->isNotEmpty())                 
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

