@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-11 text-center">
                        <h3>{{ __('views.Idea Popularity Index') }}</h3>
                    </div>
                    <div class="col-md-1 text-md-right">
                        <a class="btn btn-primary btn-block" href="{{ route('ideas.indexes') }}">{{ __('IDI') }}</a>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
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
                            <p>The Idea Popularity Index (IPI) shows us what are the most popularly supported ideas within the Universe, within any nation, and within Egora. The more people support a particilar idea in their Ideological Profiles, the higher the idea rises on the IPI.</p>
                            <p>The IPI serves two purposes. First, it is an alternative way to measure the support behind an idea. Here, the zero-point ideas count equally to the point-weighted ideas; therefore, the IPI can be a way to “test” the support for an idea before people commit any of their valuable points to that idea.</p>
                            <p>The second purpose of the IPI is to allow the ILP Members––who are the citizens of Egora––to control the Egora. Using the “Egora” idea relevance category, ILP Members can guide the development of Egora and elect the next administrator of Egora (administrator elections not available until system completion and sufficient mass).</p>
                        </div>
                      </div>
                    </div>
                </div>
                
                @include('blocks.search')
                
                @include('blocks.ideas', ['index'=>'popularity'])
                
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

