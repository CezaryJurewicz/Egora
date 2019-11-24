@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Options Menu') }}</h4>
                <div class="card">
                    <div class="card-body text-justify">
                        <ul class="offset-2">
                            <li><a href="{{ route('ilp.principles') }}">Review the ILP Principles</a></li>
                            <li><a href="{{ route('ilp.guide') }}">Guide to the Structure of the Provisional Administrative Leadership of the ILP</a></li>
                            <li><a href="{{ route('ilp.officer_petition') }}">Petition to become an ILP officer</a></li>
                            <li>Withdraw from ILP membership</li>
                        </ul>
                        
                        <div class="row mt-5">
                            <div class="offset-4 col-md-3">
                                <a class='btn btn-primary btn-block' href="{{ route('users.ideological_profile', auth('web')->user()->id) }}">{{__('Close')}}</a>
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
                