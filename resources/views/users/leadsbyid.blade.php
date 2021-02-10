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
                                <div class="col col-md-8 offset-2">
                                    <div class="text-center">
                                        <h3>{{ __('Leads') }}</h3>
                                    </div>
                                </div>
                                <div class="col col-md-2 text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ route('users.ideological_profile', $user->active_search_names->first()->hash) }}">IP</a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    @if($leads->isNotEmpty())
                                        <table class="table table-striped">
                                            <thead>
                                                <tr style="border-top: hidden;">
                                                    <th scope="col">{{ __('tables.User Category')}}</th>
                                                    <th scope="col">{{ __('tables.Search Name')}}</th>
                                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    @endif

                                    @forelse ($leads as $i=>$user)
                                                <tr>
                                                    <td>
                                                        {{$user->user_type->title}}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">
                                                        {{ $user->active_search_names->first() ? $user->active_search_names->first()->name : '-'}} 
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $user->nation->title }}                                        
                                                    </td>
                                                </tr>
                                    @empty
                                        <!--<p>@lang('user.No users found')</p>-->
                                    @endforelse

                                    @if($leads->isNotEmpty())                 
                                            </tbody>
                                        </table>
                                    @endif
                                    
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
