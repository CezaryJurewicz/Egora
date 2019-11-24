@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Member Declaration') }}</h4>
                <div class="card">
                    <div class="card-body text-justify">
                        <p>Philosopher @if(auth('web')->check()){{ auth('web')->user()->name }}@else [user’s name] @endif,</p>
                        <p>Through this function you are able to declare yourself as a member of the
                        International Logic Party (ILP). To be a member of this political party, you
                        simply have to proclaim that you agree with the five Party Principles, below.</p>
                        <p>As a member of the ILP, you will gain access to the full functionality of
                        Egora, especially being able to organize Party meetings, to vote for Party
                        candidates for government offices, and to support Party candidates’
                        campaigns financially using Egora’s revenue. You will also become a citizen
                        and proprietor of Egora, participating in the election of the Filosofos
                        Vasileus/Vasileesa, who is both the chief administrator of Egora and the
                        highest leader of the International Logic Party.</p>
                        <p>However, becoming a member of the ILP does have a cost, which is
                        transparency. This means that your membership in the ILP is public
                        knowledge. Furthermore, you can declare yourself a member of the ILP only
                        once; if you later decide that you want to withdraw yourself from ILP
                        membership, you will not be able to declare yourself as a member again.*
                        The reason for this measure is that the ILP wants only members who will
                        remain loyal to their fellow members. Be certain that you are ready for this
                        responsibility.</p>
                        <p>Cezary Jurewicz<br/>
                        Filosofos Vasileus<br/>
                        International Logic Party<br/>
                        “Transparency is the price of power”</p>

                        <p>* By declaring yourself as a member of the ILP you permit the ILP to
                        forever maintain the record of your membership. This includes any public
                        and willfully provided identifying information necessary for the maintenance
                        of this record. The ILP will continue to maintain this record, even in the event
                        of your latter withdrawal of membership.</p>
                        <p>To ensure the highest accuracy of this record and for the convenience of
                        everyone, the Egora account of ILP Members and former ILP members will
                        be undeletable (it will only be capable of deactivation). If these terms are not
                        acceptable to you, DO NOT declare yourself as an ILP member in the Egora
                        until you are ready to meet these conditions.</p>
                    </div>
                </div>
                <h3 class="mt-3">International Logic Party Principles</h3>
                <div class="text-justify">
                    @include('blocks.principles')
                </div>
                
                <div class="text-center">
                    <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                </div>
                
                @if (auth('web')->check() && auth('web')->user()->user_type->class == 'member' && auth('web')->user()->user_type->candidate)
                    <div class="text-justify mt-5">
                        Application in progress
                    </div>
                @else
                <form action="{{ route('ilp.submit_application', auth('web')->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-justify mt-5 mb-5">
                        I, <input style='width: 200px; border: none; border-bottom: 1px solid;' placeholder="(user name)" name="name">, am a member of the International Logic Party.                    
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

