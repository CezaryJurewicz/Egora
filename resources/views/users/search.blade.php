@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Users') }}</h3>
                
                <form action="{{ route('users.search') }}" method="POST">
                    <div class="form-group row">
                        <label for="q" class="col-md-2 col-form-label text-md-right">{{ __('Search') }}</label>

                        @csrf
                        <div class="col-md-7">
                            <input id="q" type="text" class="form-control @error('q') is-invalid @enderror" name="q" value="{{ old('q') }}" autofocus required>

                            @error('search')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3">
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
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Info')}}</th>
                                    <th scope="col">{{ __('tables.Ideas')}}</th>
                                    <th scope="col">{{ __('tables.Created')}}</th>
                                    <th scope="col">{{ __('tables.Updated')}}</th>
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

