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
                    <div class="card-body text-justify col-md-10 offset-md-1">
                        <p>Philosopher @if(auth('web')->check()){{ auth('web')->user()->name }}@else [user’s name] @endif,</p>
                        <p>Through this function you are able to declare yourself as a member of the
                        International Logic Party (ILP). To be a member of this political party, you
                        simply have to agree with the Five Party Principles (given below) and
                        publicly announce that you are a member.</p>
                        <p>As a member of the ILP, you will gain access to the full functionality of
                        Egora, especially being able to organize ILP meetings and to vote for ILP
                        candidates. You will also become a citizen and proprietor of Egora,
                        participating in the election of the Filosofos Vasileus/Vasileesa, who is both
                        the administrator of Egora and the highest leader of the ILP.</p>
                        <p>However, becoming a member does have a cost, which is transparency. This
                        means that your membership in the ILP is public knowledge. Furthermore,
                        you can declare yourself a member only once; if you later decide that you
                        want to withdraw yourself from ILP membership, you will not be able to
                        declare yourself as a member again.* The reason for this measure is that the
                        ILP wants only those members who remain loyal to their fellow members. <u>Be
                        certain that you are ready for this responsibility.</u></p>
                        
                        <p>Cezary Jurewicz<br/>
                        <i>Filosofos Vasileus</i><br/>
                        <b>International Logic Party</b><br/>
                        “Transparency is the price of power.”</p>

                        <p>* By declaring yourself as a member of the ILP you permit the ILP to
                        forever maintain the record of your membership. This includes a minimal
                        amount of willfully provided identifying information necessary for the
                        maintenance of this record. The ILP will continue to maintain this record,
                        even in the event of your latter withdrawal from membership.</p>
                        <p>Furthermore, to ensure the highest accuracy of this record and for the
                        convenience of everyone, the Egora accounts of ILP Members and former ILP
                        members will not be deletable. If these terms are not acceptable to you, DO
                        NOT declare yourself as a member until you are ready to meet these
                        conditions.</p>
                    </div>
                </div>
                <h3 class="mt-5">International Logic Party Principles</h3>
                <div class="text-justify col-md-10 col-11 offset-1">
                    @include('blocks.principles')
                </div>
                
                <div class="text-center mt-5">
                    <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                </div>
                
                @if (auth('web')->check() && auth('web')->user()->user_type->class == 'member' && auth('web')->user()->user_type->candidate)
                    <div class="text-justify mt-5">
                        Your declaration is in progress.
                    </div>
                @elseif (auth('web')->check() && auth('web')->user()->government_id && auth('web')->user()->government_id->status == 'submitted')
                    <div class="text-justify col-md-10 offset-md-1 mt-5">
                        Your declaration is in progress. <br/>
                        If your government ID is rejected, you will receive a notification in your Inbox.
                    </div>
                @elseif (auth('web')->check() && auth('web')->user()->government_id && auth('web')->user()->government_id->status == 'rejected')
                    <div class="text-justify col-md-10 offset-md-1 mt-5">
                        The image of your government ID that you submitted with your ILP Member Declaration was not accepted. Please submit another one.
                        
                        <form action="{{ route('users.government_id.reupload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <button type='submit' class='btn btn-primary btn-sm'>{{__('Upload ID')}}</button>
                        </form>
                    </div>
                @else
                <form action="{{ route('ilp.submit_application', auth('web')->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-justify col-md-10 offset-md-1 mt-5 mb-5">
                        I, <input class='line' value="{{ old('name') ?: '' }}" placeholder="(your name)" name="name">, am a Member of the International Logic Party.                    
                    </div>
                    <div class="text-justify col-md-10 offset-md-1 mt-5 mb-5">
                        To confirm your identity, please upload an image of your government ID (JPEG, JPG, or PNG format).

                        <div class="input-group mt-2 mb-2">
                            <input type="file" name="file" id="file" style="overflow: hidden;"/>
                            <input type="hidden" name="type" value="government_id"/>
                        </div>
                        
                        The ID number will be maintained in a secure record to prevent fraudulent activity. Your information will not be shared with anyone.                        
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a class='btn btn-primary btn-block' href="{{ route('users.ideological_profile', auth('web')->user()->active_search_names->first()->hash) }}">{{__('some.Cancel and Close')}}</a>
                        </div>
                        <div class="col-md-3 offset-md-6">
                            <button type='submit' class='btn btn-ilp btn-block'>{{__('some.Save and Close')}}</button>                            
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

