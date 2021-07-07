@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <h3>{{ __('Default Leads') }}</h3>
                    </div>
                    <div class="col-md-2">
                        <a class='btn btn-sm btn-primary' href="{{ route('users.index') }}">{{__('Add New')}}</a>
                    </div>
                </div>
                <div class="mt-3">
                    @if($users->isNotEmpty())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Name')}}</th>
                                    <th scope="col">{{ __('Created')}}</th>
                                    <th scope="col">{{ __('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($users as $i=>$user)
                                <tr>
                                    <th scope="row">{{$user->id}}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->createdDate() }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-primary mb-2" href="{{ route('users.profile', $user->id) }}">@lang('some.View')</a>

                                        <form action="{{ route('remove_default_lead',[$user->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-secondary'>{{__('Remove')}}</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>   
                    @empty
                        <p>@lang('Empty')</p>
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

