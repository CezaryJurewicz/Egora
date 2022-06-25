@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Withdraw Officer Petition') }}</h4>
                
                <div class="text-center mt-5">
                    <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                </div>
                
                <form action="{{ route('ilp.cancel_officer_application_proceed', auth('web')->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-justify col-md-10 offset-md-1 mt-5 mb-5">
                        I, <input class="line" value="{{ old('name') ?: '' }}" placeholder=" (user name)" name="name">, am withdrawing my petition to be 
                        <i>Filosofos tou Dromou</i> of the <u>&nbsp; {{ auth('web')->user()->nation->title }} &nbsp;</u> branch
                        of the International Logic Party for the Polis of <input class="line" value="{{ old('polis') ?: auth('web')->user()->petition->polis }}" placeholder=" (your polis)" name="polis">.
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
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

