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
                                        <h3>{{ __('Followers') }} ({{ $total }})</h3>
                                    </div>
                                </div>
                                <div class="col col-md-2 text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">
                                    @if(is_egora('municipal')) MM 
                                    @elseif(is_egora('community')) CM
                                    @else IP @endif
                                    </a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    @if($followers->isNotEmpty())
                                        <table class="table table-striped" style="table-layout:fixed;">
                                            <thead>
                                                <tr style="border-top: hidden;">
                                                    <th scope="col">{{ __('tables.User Category')}}</th>
                                                    <th scope="col">{{ __('tables.Search Name')}}</th>
                                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    @endif

                                    @forelse ($followers as $i=>$u)
                                                <tr>
                                                    <td>
                                                        {{$u['user_type']['title']}}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('users.ideological_profile', $u['active_search_name_hash']) }}">
                                                        {{ $u['active_search_name'] }} 
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $u['nation']['title'] }}                                        
                                                    </td>
                                                </tr>
                                    @empty
                                        <!--<p>@lang('user.No users found')</p>-->
                                    @endforelse

                                    @if($followers->isNotEmpty())                 
                                            </tbody>
                                        </table>
                                        {{ $followers->links() }}
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
