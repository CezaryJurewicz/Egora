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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 col-centered">
                        <ul class="text-justify">
                            <li><a href="{{ route('ilp.principles') }}">ILP Principles</a></li>
                            <li><a href="{{ route('ilp.founding_members') }}">ILP Founding Members</a></li>
                            <li><a href="{{ route('ilp.guide') }}">Provisional Administrative Leadership of the ILP</a></li>
                            @if (auth('web')->check() && auth('web')->user()->can('submit_officer_application', auth('web')->user()))
                            <li><a href="{{ route('ilp.officer_petition') }}">Petition to become an ILP officer</a></li>
                            @endif
                            @if (auth('web')->check() && auth('web')->user()->can('cancel_officer_application', auth('web')->user()))
                            <li><a href="{{ route('ilp.cancel_officer_application', auth('web')->user()->id ) }}">Cancel petition to become an ILP officer</a></li>
                            @endif
                            @if (auth('web')->check() && auth('web')->user()->can('withdraw_from_ilp', auth('web')->user()))
                            <li><a href="{{ route('users.withdraw_from_ilp', auth('web')->user()->id ) }}">Withdraw from ILP membership</a></li>
                            @endif
                        </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a class='btn btn-ilp col-md-2 btn-sm' href="{{ route('users.ideological_profile', auth('web')->user()->id) }}">{{__('Close')}}</a>
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
                