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
                    <div class="card-body text-justify">
                        <p>Philosopher @if(auth('web')->check()){{ auth('web')->user()->name }}@else [user’s name] @endif,</p>
                        
                        <p>Through this function you are able to open a petition to become a basic
                        officer of your national branch of the International Logic Party (ILP). ILP
                        Members from your nation, including yourself, will be able to give you their
                        support by visiting your user profile and clicking the “Add My Signature”
                        button in your user information section. If you succeed in attaining the
                        support of at least 23 ILP Members, you will attain the first officer rank of
                        Filosofos tou Dromou (Philosopher of the Street). If your support falls
                        below 23 signatures for more than 23 hours, you will lose your officer status.</p>
                        <p>To attain the higher officer ranks of Filosofos tis Polis, Filosofos tis Gis, and
                        Filosofos Ethnikos, you will have to follow the election processes of your
                        national ILP branch. If those have not yet been officially established by your
                        nation, follow the provisional protocol in the document linked below. And,
                        of course, to attain the highest rank of Filosofos Vasileus/Vasileesa, simply
                        follow the Fourth Principle of the International Logic Party.</p>
                        <p>However, be aware that by serving as an ILP officer you become a public
                        figure and your privacy will be reduced. Most notably, your profile will
                        become searchable, regardless of your previous privacy setting.</p>

                        <div class="text-center">
                        <a href="{{ route('ilp.guide') }}">Guide to the Structure<br/>
                        of the Provisional Administrative Leadership<br/>
                        of the International Logic Party</a>
                        </div>

                        <p>Cezary Jurewicz<br/>
                        Filosofos Vasileus<br/>
                        International Logic Party<br/>
                        “Transparency is the price of power”</p>

                    </div>
                </div>
                
                <div class="text-center">
                    <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                </div>
                
                @if (auth('web')->check() && auth('web')->user()->user_type->class == 'officer' && auth('web')->user()->user_type->candidate)
                    <div class="text-justify mt-5">
                        Application in progress
                    </div>
                @else
                <form action="{{ route('ilp.submit_officer_application', auth('web')->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-justify mt-5 mb-5">
                        I, <input style='width: 200px; border: none; border-bottom: 1px solid;' placeholder="(user name)" name="name">, am opening 
                        my petition to become Filosofos tou Dromou of the <u>&nbsp; {{ auth('web')->user()->nation->title }} &nbsp;</u> branch's
                        of International Logic Party for the Polis of __(your polis)__.
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <a class='btn btn-secondary btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                        </div>
                        <div class="col-md-3 offset-6">
                            <button type='submit' class='btn btn-primary btn-block'>{{__('some.Save and Close')}}</button>                            
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

