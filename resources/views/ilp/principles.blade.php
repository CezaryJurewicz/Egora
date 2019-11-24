@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Principles') }}</h4>
                <div class="card">
                    <div class="card-body text-justify">
                        @include('blocks.principles')
                        <div class="text-center">
                            <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-3">
                                <a class='btn btn-primary btn-block' href="{{ route('users.ideological_profile', auth('web')->user()->id) }}">{{__('Close')}}</a>
                            </div>
                            <div class="col-md-3 offset-6">
                                <a class='btn btn-primary btn-block' href="{{ url()->previous() }}">{{__('ILP Functions')}}</a>                            
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

