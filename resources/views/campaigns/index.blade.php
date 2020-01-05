@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3>{{ __('Campaign Manager') }}</h3>
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
                        <div class="card-body col-md-10 offset-1">
                            <p>This campaign organizing function is only <u>minimally</u> operational –
                            there are many critical filters and other essential features that are not
                            yet available. For now, the purpose of this function is only to
                            demonstrate the potential of Egora’s algorithm to highlight persons
                            who most closely represent the will of the people.</p>
                            <p>The Candidate Score algorithm is very simple: of all of the ideas that
                            a candidate supports (up to 46), we total the IDI Points of the 23
                            ideas with the most IDI Points.</p>
                            <center>
                                <p>Please support our work in making Egora everything it can be: <br>
                                <a href="https://GoFundMe.com/Egora-ILP23">GoFundMe.com/Egora-ILP23</a></p>                                
                            </center>
                            
                        </div>
                      </div>
                    </div>
                </div>
                
                <div class="clearfix">&nbsp;</div>
                
                <div class="panel-body">
                    
                    @if (auth('web')->check() && auth('web')->user()->can('create', App\Campaign::class))
                    <div class="text-center">
                        <h5 class="col-md-10 offset-1">I announce my candidacy to serve and promote my sincerely held ideas in the governance of <br/> {{ auth('web')->user()->nation->title }}.</h5>

                        <form action="{{ route('campaigns.store') }}" method="POST">
                            @csrf
                            <button type='submit' class='btn btn-sm btn-primary mt-4 btn-static-200'>{{__('Submit')}}</button>
                        </form>
                    </div>
                    @endif       
                    
                    @if (auth('web')->check() && auth('web')->user()->campaign && auth('web')->user()->can('delete', auth('web')->user()->campaign))
                    <div class="text-center">
                        <h5 class="col-md-10 offset-1">I withdraw my candidacy to champion my ideas in the governance of <br/> {{ auth('web')->user()->nation->title }}.</h5>

                        <form action="{{ route('campaigns.delete', auth('web')->user()->campaign) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                            <button type='submit' class='btn btn-sm btn-primary mt-4 btn-static-200'>{{__('Submit')}}</button>
                        </form>
                    </div>
                    @endif       

                </div>
                
                <div class="panel-body">
                    <div class="text-center mt-5 mb-3">
                        <h4>Candidate Rank Listing</h4>
                    </div>                

                    <form action="{{ route('campaigns.search') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT"/>
                        <div class="form-group row">
                            <label for="nation" class="offset-1 col-md-2 col-form-label text-md-right">{{ __('Nation:') }}</label>

                            <div class="col-md-6">
                                <div id="NationSearch" value="{{ $nation ?? '' }}"></div>

                                @error('nation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                    <button type='submit' class='btn btn-sm btn-primary mt-4 btn-static-200'>{{__('some.Generate')}}</button>
                            </div>
                        </div>

                    </form>
                    
                    <div class="offset-2 col-md-6 col-centered">
                    @if($rows->isNotEmpty()) 
                        <div class="row mt-5 mb-3">
                            <div class="col-md-2"><b>Rank</b></div>
                            <div class="col-md-4"><b>Score</b></div>
                            <div class="col-md-6"><b>Candidate</b></div>
                        </div>

                        @foreach($rows as $points => $row)
                        <div class="row mb-3">
                            <div class="col-md-2">{{$loop->iteration}}</div>
                            <div class="col-md-4">{{ number_format($points) }}</div>
                            <div class="col-md-6">
                                @foreach($row as $names)
                                <div>
                                    <a href="{{ route('users.ideological_profile', $names['user_id']) }}">
                                    {{ $names['search_name'] }}
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

