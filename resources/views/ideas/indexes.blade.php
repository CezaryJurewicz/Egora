@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-11 text-center">
                        <h3>{{ __('views.Idea Dominance Index') }}</h3>
                    </div>
                    <div class="col-md-1 text-md-right">
                        <a class="btn btn-primary btn-block" href="{{ route('ideas.popularity_indexes') }}">{{ __('IPI') }}</a>
                    </div>
                </div>
                
                <div class="accordion mb-3" id="accordion">
                    <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                      <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                          <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Guide
                          </button>
                        </h2>
                      </div>

                      <div id="collapseOne" class="collapse show1" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <p>The Idea Dominance Index (IDI) shows us what are the most strongly supported ideas within the Universe and within any nation. The more points the people give to a particilar idea in their Ideological Profiles, the higher the idea rises on the IDI.</p>
                            <p>If you think there is an idea that deserves to be higher on the IDI, come to International Logic Party meetings (for ILP Members) or “Egora Society” meetings (for non Members) to explain to the people why they should support this idea. If you think there is an
                                idea that does not deserve to be so high on the IDI, come to ILP or “Egora Society” meetings to offer the people some better ideas to support. Through Egora the people have the power, and The Idea Dominance Index is the true measurement of that power.</p>
                        </div>
                      </div>
                    </div>
                </div>
                
                @include('blocks.search')

                @include('blocks.ideas', ['index'=>'dominance'])

            </div>
        </div>
    </div>
    </div>
</div>
@endsection

