@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="accordion mb-3" id="accordion">
                <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                  <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                      <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Letter of introduction from Egora administrator 
                      </button>
                    </h2>
                  </div>

                  <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body col-md-10 offset-1">
                        <p>Philosopher {{ (auth('web')->user()?:auth('admin')->user())->name }} –</p>
                        <p>Whether or not you were a philosopher before, now that you are here
                        and have an Ideological Profile, you are a philosopher. Egora turns
                        everyone who uses it into a philosopher, and we’re all better off for
                        it. Thank you for joining us so that we can make the world a better
                        place by learning from one another.</p>
                        <p>That said, I want to give you a brief description of how everything
                        works:</p>
                        <ol class="bold">
                        <li>By filling out your Ideological Profile (IP), you assemble the
                        ideas and issues that identify you. This process alone already has two
                        great benefits. First, through your IP you can quickly and
                        conveniently demonstrate to others what you stand for and why. But
                        even more importantly, you can easily and effectively define
                        yourself to yourself – and there is no higher goal for any philosopher
                        than to “Know Thyself”.</li>
                            
                        <li>As you build your IP, you are actually giving support to those
                        ideas on the Idea Dominance Index and the Idea Popularity Index.
                        These two indexes respectively keep track of how strongly and how
                        popularly various ideas are supported in the Egora among its users.
                        This knowledge gives us––the people––tremendous power regarding
                        what we can demand from our national governments.</li>
                        <li>Egora enables us to organize real-life meetings so that we can
                        come together in small groups to discuss any of those ideas in detail,
                        to deliberate alternatives, and to organize politically under the name
                        of the International Logic Party (ILP). Anyone is able declare

                        themself as a member of the ILP in the Egora, if only they agree
                        with the five Party Principles (see Home page, "ILP" button).</li>

                        <li>Using a simple algorithm, Egora then enables us to cross-
                        reference the most strongly supported ideas on the Idea Dominance

                        Index to ILP Members’ Ideological Profiles. In doing so, we
                        nominate political candidates who actually represent what the people
                        really want. These candidates go on to campaign for national
                        government offices with the support of the International Logic Party.</li>
                        <li>Lastly, Egora is not a business; it is a Republic. The ILP
                        Members own it; the ILP Members control it. Thus, all of the surplus
                        revenue that Egora generates is distributed to ILP Members, based
                        on how much they attend ILP meetings. The ILP Members then
                        decide which ILP candidates to support with their earned credits.</li>
                        </ol>
                        
                        <p>There are many details left out from this description; but basically,
                        we are putting all corrupt politicians out of business. We, the people,
                        will control all governments!</p>
                        
                        <p>However, there is one problem... the construction of Egora is not
                        complete, and we cannot finish building it without you. We have
                        been able to come this far thanks to the support of a handful of
                        brilliant people who have very limited resources. Most notably, we
                        express tremendous gratitude to Steve Olotu for helping to build this
                        platform and to Taras Hryniw for hosting it. But we need your help
                        to keep going. So, if you like what we are doing, please contribute
                        whatever you can to help us finish Egora. Every donation counts, no
                        matter how small.</p>
                        
                        <center><a href="https://GoFundMe.com/Egora-ILP23">GoFundMe.com/Egora-ILP23</a></center>

                        <p>In any case, we appreciate all else you do for our common cause,
                        whether that’s organizing the ILP, coming to ILP meetings, telling
                        your friends, or just using Egora. With you, we are unstoppable!</p>
                        
                        <p>Cezary Jurewicz<br/>
                        Filosofos Vasileus<br/>
                        International Logic Party<br/>
                        “Transparency is the price of power.”</p>
                    </div>
                  </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Egora Users</div>
                <div class="card-body text-center">
                    All Nations - {{ $total_verified_users }}<br/>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">ILP Members</div>
                <div class="card-body text-center">
                    All Nations - {{ $total_verified_ipl_users }}<br/>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">ILP Members by Nation</div>
                <div class="card-body text-center">
                @foreach($group_by_nation as $nation)
                {{ $nation->title }} - {{ $nation->users->count() }}<br/>
                @endforeach
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
