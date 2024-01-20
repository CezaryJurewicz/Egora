@extends('layouts.app')

@section('content')
<div class="container-lg">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-1">
                        @if (empty($sort)) 
                        <a class="btn btn-primary btn-block" href="{{ route('ideas.popularity_indexes', array_merge(\Arr::only(\Request::query(),['sort', 'relevance', 'unverified', 'nation','page', 'community', 'municipality']), ['sort' => 'date']) ) }}">{{ __('Newest Ideas') }}</a>
                        @else
                        <h5 class="pt-2">{{ __('Newest Ideas') }}</h5>
                        @endif
                    </div>
                    @if (is_egora())
                    <div class="col-md-4 text-md-right mb-1">
                        <a class="btn btn-primary btn-block" href="{{ route('ideas.indexes') }}">{{ __('Idea Dominance Index') }}</a>
                    </div>
                    @endif
                    <div class="col-md-4 text-center mb-1">
                        @if (empty($sort)) 
                            <h5 class="pt-2">{{ __('Idea Popularity Index') }}</h5>
                        @else
                            <a class="btn btn-primary btn-block" href="{{ route('ideas.popularity_indexes') }}">{{ __('Idea Popularity Index') }}</a>
                        @endif
                    </div>
                </div>
                
                <div class="clearfix">&nbsp;</div>
                
                @if (is_egora())
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
                        <div class="card-body col-md-10 offset-md-1">
                            @if (empty($sort)) 
                            <p>The Idea Popularity Index (IPI) shows what are the most <u>popularly</u>
                            supported ideas universally, within any nation, and within Egora. As more philosophers include a particular idea in their Ideological Profiles,
                            the idea rises higher on the IPI.</p>
                            <p>The IPI serves two main purposes. First, it is an alternative way to
                            measure the support behind an idea. Here, the zero-point ideas count
                            equally to the point-weighted ideas; therefore, the IPI can be a way to test the support for an idea before philosophers give to it any of their valuable points. Second, the IPI enables the ILP Members––who are
                            the citizens of Egora––to control Egora. Using the “Egora” category of idea relevance, ILP Members can make decisions about the administration of Egora. Most importantly, however, ILP Members can use the
                            IPI to elect the next administrator of Egora (not yet available).</p>
                            @else
                            <p>This is simply a listing of ideas according to how recently they were introduced.</p>
                            @endif
                        </div>
                      </div>
                    </div>
                </div>
                @endif
                
                @include('blocks.search')
                
                @include('blocks.ideas', ['index'=>'popularity'])
                
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

