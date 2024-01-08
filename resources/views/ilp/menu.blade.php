@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Options Menu') }}</h4>
                
                @if (auth('web')->check() && auth('web')->user()->user_type->isOfficer)
                <div class="text-justify">
                    <div class="clearfix">&nbsp;</div>
                    <div class="accordion mb-3" id="accordion">
                        <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                          <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Officer Guide
                              </button>
                            </h2>
                          </div>

                          <div id="collapseOne" class="collapse @if($show) show @endif" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body col-md-10 offset-md-1">
                                
                                <p>Philosopher @if(auth('web')->check()){{ auth('web')->user()->name }}@else [user’s name] @endif – </p>
                                <p>Congratulations! You are now an officer of the International Logic
                                Party. Welcome to our noble ranks!</p>

                                <p>You are a proven leader, and you know what to do next, or else you
                                wouldn’t have come this far.</p>
                                
                                <p>See you in the Egora!</p>

                                <p>Cezary Jurewicz<br/>
                                <i>Filosofos Vasileus</i><br/>
                                <b>International Logic Party</b><br/>
                                “Transparency is the price of power.”</p>
                                
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 col-centered">
                        <ul class="text-left">
                            <li><a href="{{ route('ilp.principles') }}">ILP Principles</a></li>
                            <li><a href="{{ route('ilp.founding_members') }}">ILP Founding Members</a></li>
                            <li><a href="{{ route('ilp.guide') }}">Provisional Administrative Leadership of the ILP</a></li>
                            @if (auth('web')->check() && auth('web')->user()->can('submit_officer_application', auth('web')->user()))
                            <li><a href="{{ route('ilp.officer_petition') }}">Petition to become an ILP officer</a></li>
                            @endif
                            @if (auth('web')->check() && auth('web')->user()->can('cancel_officer_application', auth('web')->user()))
                            <li><a href="{{ route('ilp.cancel_officer_application', auth('web')->user()->id ) }}">Withdraw Officer Petition</a></li>
                            @endif
                            @if (auth('web')->check() && auth('web')->user()->can('withdraw_from_ilp', auth('web')->user()))
                            <li><a href="{{ route('users.withdraw_from_ilp', auth('web')->user()->id ) }}">Withdraw from ILP membership</a></li>
                            @endif
                        </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a class='btn btn-ilp col-md-2 btn-sm' href="{{ route('users.ideological_profile', auth('web')->user()->active_search_names->first()->hash) }}">{{__('Close')}}</a>
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
                