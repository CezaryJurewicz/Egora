@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Officer Petition') }}</h4>
                <div class="card">
                    <div class="card-body text-justify col-md-10 offset-md-1">
                        <p>Philosopher @if(auth('web')->check()){{ auth('web')->user()->name }}@else [user’s name] @endif,</p>
                        
                        <p>Through this function you are able to open a petition to become an officer of
                        your provisional national branch of the ILP. ILP Members from your nation,
                        including yourself, will be able to give you their support by clicking the “Add
                        My Name” button under your petition. If you receive the support of at least
                        5 ILP Members, you will attain the first officer rank of <b><i>Filosofos tou
                        Dromou</i></b>, meaning Philosopher of the Street. If your support falls below 5
                        names, you will lose your officer status.</p>
                        <p>To learn more about the structure of the Provisional Administrative
                        Leadership of the ILP, including how to attain the higher officer ranks of
                        <i>Filosofos tis Polis</i>, <i>Filosofos tis Gis</i>, and <i>Filosofos Ethnikos</i>, familiarize
                        yourself with the protocol in this document:</p>

                        <div class="text-center">
                        <a href="{{ route('ilp.guide') }}">Provisinal Administrative Leadership<br/>
                        of the International Logic Party</a>
                        </div>

                        <p>Cezary Jurewicz<br/>
                        <i>Filosofos Vasileus</i><br/>
                        <b>International Logic Party</b><br/>
                        “Transparency is the price of power.”</p>

                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                </div>
                
                @if (auth('web')->check() && auth('web')->user()->user_type->class == 'officer' && auth('web')->user()->user_type->candidate)
                    <div class="text-justify mt-5">
                        Application in progress
                    </div>
                @else
                <form action="{{ route('ilp.submit_officer_application', auth('web')->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-justify col-md-10 offset-md-1 mt-5 mb-5">
                        I, <input class="line" value="{{ old('name') ?: '' }}" placeholder=" (user name)" name="name">, am opening 
                        my petition to become <i>Filosofos tou Dromou</i> of the <u>&nbsp; {{ auth('web')->user()->nation->title }} &nbsp;</u> branch
                        of the International Logic Party for the Polis of <input class="line" value="{{ old('polis') ?: '' }}" placeholder=" (your polis)" name="polis">.
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a class='btn btn-secondary btn-ilp btn-block' href="{{ route('users.ideological_profile', auth('web')->user()->active_search_names->first()->hash) }}">{{__('some.Cancel and Close')}}</a>
                        </div>
                        <div class="col-md-3 offset-md-6">
                            <button type='submit' class='btn btn-primary btn-ilp btn-block'>{{__('some.Save and Close')}}</button>                            
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

