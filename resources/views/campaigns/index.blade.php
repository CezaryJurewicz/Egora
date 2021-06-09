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
                                <a href="https://GoFundMe.com/EgoraILP23">GoFundMe.com/EgoraILP23</a></p>                                
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
                            <div class="form-group row">
                                <label for="subdivision" class="offset-1 col-md-3 col-form-label text-left">{{ __('Administrative Level:') }}</label>

                                <div class="col-md-6">
                                    <select id="subdivision" type="text" class="form-control @error('relevance') is-invalid @enderror" name="subdivision" value="{{ old('subdivision') }}">
                                    <option @if ((old('subdivision') && old('subdivision')==0) || (isset($subdivision) && $subdivision == 0)) selected @endif value="0">Head of State</option>
                                    @for($i=1; $i<24; $i++)
                                    <option @if ((old('subdivision') && old('subdivision')==$i) || (isset($subdivision) && $subdivision == $i))  selected @endif @if (!isset($subdivisions[$i])) disabled @else value="{{$i}}" @endif>Subdivision {{$i}} - {{ (isset($subdivisions[$i]) ? $subdivisions[$i]->title : '') }}</option>
                                    @endfor
                                    </select>

                                    @error('subdivision')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>                    

                            <div class="form-group row">
                                <label for="party" class="offset-1 col-md-3 col-form-label text-left">{{ __('Party Affiliation:') }}</label>

                                <div class="col-md-6 text-left">
                                    @if (auth('web')->user()->user_type->isIlp)
                                        International Logic Party
                                    @else                                    
                                        <div id="PartySearch" value="{{ old('party') }}"></div>
                                        @error('party')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endif
                                </div>
                            </div>                    
                            
                            <button type='submit' class='btn btn-sm btn-primary mt-4 btn-static-200'>{{__('Submit')}}</button>
                        </form>
                    </div>
                    @endif       
                    
                    @if (auth('web')->check() && auth('web')->user()->campaign && auth('web')->user()->can('delete', auth('web')->user()->campaign))
                    <div class="text-center">
                        <h5 class="col-md-10 offset-1">I withdraw my candidacy to champion my ideas in the governance of <br/> {{ auth('web')->user()->nation->title }}.</h5>

                            <div class="form-group row">
                                <label for="subdivision" class="offset-1 col-md-3 col-form-label text-left">{{ __('Administrative Level:') }}</label>

                                <div class="col-md-6 text-md-left">
                                    <p class="mt-2 mb-0">
                                    @if (auth('web')->user()->campaign->subdivision)
                                    Subdivision {{auth('web')->user()->campaign->order}} - {{ auth('web')->user()->campaign->subdivision->title }}
                                    @else
                                    Head of State
                                    @endif
                                    </p>
                                </div>
                            </div>                    
                        
                        
                        
                        <form action="{{ route('campaigns.delete', auth('web')->user()->campaign) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                            
                            <div class="form-group row">
                                <label for="password" class="offset-1 col-md-3 col-form-label text-left">{{ __('Confirm Password:') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>                    
                            
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
                            <label for="subdivision" class="offset-1 col-md-3 col-form-label">{{ __('Administrative Level:') }}</label>

                            <div class="col-md-6">
                                <select id="subdivision" type="text" class="form-control @error('subdivision') is-invalid @enderror" name="subdivision" value="{{ old('subdivision') }}">
                                <option @if ((old('subdivision') && old('subdivision')==0) || (isset($subdivision) && $subdivision == 0)) selected @endif value="0">Head of State</option>
                                @for($i=1; $i<24; $i++)
                                <option @if ((old('subdivision') && old('subdivision')==$i) || (isset($subdivision) && $subdivision == $i))  selected @endif @if (!isset($subdivisions[$i])) disabled @else value="{{$i}}" @endif>Subdivision {{$i}} - {{ (isset($subdivisions[$i]) ? $subdivisions[$i]->title : '') }}</option>
                                @endfor
                                </select>

                                @error('subdivision')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                    
                        
                        <div class="form-group row">
                            <div class="col-md-6 offset-1">
                                <div class="row">
                                <label for="status" class="col-md-5 col-form-label">{{ __('Qualification Status:') }}</label>
                                </div>
                                <div class="row">
                                <small class="col-md-12">See the bottom of any user's Ideological Profile for details about this function</small>
                                </div>
                            </div>
                            <div class="col-md-2 offset-1">
                                <select id="status" type="text" class="form-control @error('status') is-invalid @enderror" name="status" value="{{ old('status') }}">
                                <option @if ((old('status') && old('status')==0) || (isset($status) && $status == 0)) selected @endif value="0">Qualified</option>
                                <option @if ((old('status') && old('status')==1) || (isset($status) && $status == 1)) selected @endif value="1">Disqualified</option>
                                </select>

                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                    
                        
                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                    <button type='submit' class='btn btn-sm btn-primary mt-4 btn-static-200'>{{__('Display')}}</button>
                            </div>
                        </div>

                    </form>
                    
                    <div class="offset-1 col-md-10 col-centered">
                    @if($rows->isNotEmpty()) 
                        <div class="row mt-5 mb-3">
                            <div class="col-md-1"><b>Rank</b></div>
                            <div class="col-md-1"><b>Score</b></div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4"><b>Candidate</b></div>
                                    <div class="col-md-2 text-center"><b>Qualification</b></div>
                                    <div class="col-md-3 text-center"><b>Seniority (IP)</b></div>
                                    <div class="col-md-3 text-center"><b>Seniority (Candidate)</b></div>
                                </div>
                            </div>
                        </div>

                        @foreach($rows as $points => $row)
                        <div class="row mb-3">
                            <div class="col-md-1">{{$loop->iteration}}</div>
                            <div class="col-md-1">{{ number_format($points) }}</div>
                            <div class="col-md-9">
                                @foreach($row as $names)
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{ route('users.ideological_profile', $names['hash']) }}">
                                        {{ $names['search_name'] }}
                                        </a>
                                        <br/>
                                        <small>{{ $names['affiliated'] }}</small>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        @if ($names['qualification'])
                                            {{ $names['qualification'] }}%
                                        @else 
                                            -
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-center"> {{ $names['seniority_ip'] ? $names['seniority_ip']->diffForHumans() : '-' }}</div>                            
                                    <div class="col-md-3 text-center"> {{ $names['seniority_campaign']->diffForHumans() }}</div>                            
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

