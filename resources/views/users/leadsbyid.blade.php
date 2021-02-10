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
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    @foreach($leads as $u)
                                    @if($u->active_search_names->first())
                                    <div class="row col-md-12">
                                    <a href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                    {{ $u->active_search_name ?? $u->id }}
                                    </a>
                                    </div>
                                    @endif
                                    @endforeach
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
