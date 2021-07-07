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
                        and have begun developing your Ideological Profile, you are a
                        philosopher. Egora turns everyone who uses it into a philosopher,
                        and we’re all better off for it. Thank you for joining us so that we
                        can make the world a better place by learning from one another.</p>
                        <p>Now, I want to give you a brief description of how things work:</p>
                        <ol class="bold">
                        <li>By filling out your Ideological Profile (IP), you assemble the
                        ideas and issues that identify you. This process alone already has two
                        great benefits. First, through your IP you can quickly and
                        conveniently demonstrate to others what you stand for and why. But
                        most importantly, you can easily and effectively define yourself to
                        <u>yourself</u>, and there is no higher goal for any philosopher than to
                        “Know Thyself”.</li>
                        <li>As you build your IP, you are actually giving support to those
                        ideas on the Idea Dominance Index (IDI) and the Idea Popularity
                        Index (IPI). These two indexes keep track of how strongly (IDI) and
                        how popularly (IPI) various ideas are supported in Egora among the
                        users. This knowledge gives us––the people––tremendous power
                        regarding what we can demand from our governments.</li>
                        <li>Egora enables us to organize meetings (in-person or online) so
                        that we can come together in small groups to discuss any of those
                        ideas in detail, to deliberate alternatives, and to organize politically
                        as the <i>International Logic Party</i> (ILP). Anyone is able declare
                        themself as a member of the ILP in Egora if they agree with the Five
                        Party Principles (see Home screen, “ILP” button).</li>
                        <li>Using a simple algorithm, Egora then enables us to cross-reference 
                        the IPs of ILP Members who want to be political
                        candidates to the most strongly supported ideas on the IDI. By doing
                        this, we can find political candidates who actually represent what the
                        people really want. These candidates then go on to campaign for
                        government offices, as the nominees of the ILP. If any politician ever
                        fails us, we have other candidates already waiting to replace them.</li>
                        <li>Lastly, Egora is not a business; it is a <i>Republic</i>. The ILP
                        Members own it, and the ILP Members control it. Thus, all of
                        Egora’s surplus revenue (revenue not used for maintenance) is
                        distributed to ILP Members, based on how much they attend ILP
                        meetings. The ILP Members then decide which ILP candidates to
                        support with their earned credits.</li>
                        </ol>
                        
                        <p>There are many details left out from this description; but basically,
                        we are putting all corrupt politicians out of business. We––the
                        people––are taking control of all governments!</p>
                        
                        <p>However, there is one problem – the construction of Egora is not
                        complete, and we cannot finish building it without you. We have
                        been able to come very far thanks to the support of a handful of
                        people who have very limited resources. But we need your help to
                        keep going. So if you like what we are doing, please contribute
                        whatever you can to help us finish Egora. Every donation counts, no
                        matter how small.</p>

                        <center><a href="https://GoFundMe.com/EgoraILP23">GoFundMe.com/EgoraILP23</a></center>
                        <br/>
                        
                        <p>In any case, we appreciate all else you do for our cause, whether
                        that’s leading the ILP, organizing meetings, sharing Egora with
                        others, or just having fun with ideas. With you, we are unstoppable!</p>
                        
                        <p>Cezary Jurewicz<br/>
                        <i>Filosofos Vasileus</i><br/>
                        <b>International Logic Party</b><br/>
                        “Transparency is the price of power.”</p>
                    </div>
                  </div>
                </div>
            </div>

            @include('blocks.stats')
            
        </div>
    </div>
</div>
@endsection
