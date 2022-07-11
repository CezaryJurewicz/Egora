@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3>{{ __('Meeting Organizer') }}</h3>
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
                        <div class="card-body col-md-10 offset-md-1">
                            <p>The Meeting Organizer function is primarily intended for scheduling
                            ILP meetings, but other democratic events are welcome too,
                            especially Citizen Assembly meetings. Below are the key points
                            about how ILP meetings work and how this function serves to
                            support them. However, please note this function is only minimally
                            operational, and there are many important features yet to be
                            developed.</p>
                            <ul>
                                <li>An ILP Member can schedule up to 2 meetings, up to 46 days in
                                    advance.</li>
                                <li>Up to 22 guest Members may attend to participate (total of 23).
                                Anyone else may attended only as audience, space permitting.
                                Attendees should communicate with the organizer to ensure
                                accommodations.</li>
                                <li><u>Meetings must be free to attend</u>. Suggested places are public
                                spaces, such as library or university meeting rooms. If a meetings
                                takes place in a business location, such as a cafe, there must be no
                                purchase required of the guest participants.</li>
                                <li>The official ILP meeting format:
                                    <ul>
                                        <li>Attendance</li>
                                        <li>Organizer introduction and presentation (up to 23
                                            minutes)</li>
                                        <li>Discussion, presided by the organizer or another
                                            Member, until majority calls for adjournment</li>
                                        <li>Recess (23 minutes)</li>
                                        <li>Closing remarks by guest participants (2-3 minutes each)</li>
                                        <li>Organizer’s closing remarks (2-3 minutes)</li>
                                    </ul>
                                </li>
                                <li>If you’re not having fun in politics, you’re not doing it right!</li>
                            </ul>
                            
                            <p>We count on your support in making Egora everything it can be. To
                            contribute your development ideas, please use the “Egora
                            Development” community. To contribute financially, go to</p>
                            <center><a href="https://GoFundMe.com/EgoraILP23">GoFundMe.com/EgoraILP23</a></center>
                        </div>
                      </div>
                    </div>
                </div>
                
                @if (auth('web')->check() && auth('web')->user()->can('create', App\Meeting::class))
                <div class="text-center mt-5">
                <h4>Schedule a Meeting</h4>
                </div>
                <form autocomplete="off" action="{{ route('meetings.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group row">
                        <div class="offset-1 col-md-12 row">
                            <label for="country" class="col-md-2 col-form-label text-md-right">{{ __('Country') }}</label>

                            <div class="col-md-6">
                                <div id="CountrySearch" class="@error('country') is-invalid @enderror" value="{{ old('country') }}"></div>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>  

                    <div class="form-group row">
                        <div class="offset-1 col-md-12 row">
                            <label for="city" class="col-md-2 col-form-label text-md-right">{{ __('Town') }}</label>

                            <div class="col-md-6">
                                <div id="CitySearch" value="{{ old('city') }}" cssClass="form-control @error('city') is-invalid @enderror"></div>
                                <div id="CountrySearch" class="@error('country') is-invalid @enderror" value="{{ old('country') }}"></div>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>  
                    
                    <div class="form-group row">
                        <div class="offset-1 col-md-12 row">
                            <label for="date" class="col-md-2 col-form-label text-md-right">{{ __('Date') }}</label>

                            <div class="col-md-2">
                                <div id="dateInput" class="@error('date') is-invalid @enderror" cssClass="@error('date') is-invalid @enderror form-control react-datepicker-ignore-onclickoutside"></div>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <label for="time" class="col-md-2 col-form-label text-md-right">{{ __('Time (24 hour clock)') }}</label>

                            <div class="col-md-2">
                                <div class="timeInput @error('time') is-invalid @enderror" value='now' cssClass="@error('time') is-invalid @enderror form-control react-datepicker-ignore-onclickoutside"></div>
                                @error('time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="offset-1 col-md-12 row">
                            <label for="address" class="col-md-2 col-form-label text-md-right">{{ __('Location/Address') }}</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="offset-1 col-md-12 row">
                            <label for="topic" class="col-md-2 col-form-label text-md-right">{{ __('Topic') }}</label>

                            <div class="col-md-6">
                                <input id="topic" type="text" class="form-control @error('topic') is-invalid @enderror" name="topic" value="{{ old('topic') }}">
                                @error('topic')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="offset-1 col-md-12 row">
                            <label for="comments" class="col-md-2 col-form-label text-md-right">{{ __('Details') }}</label>

                            <div class="col-md-6">
                                <textarea id="comments" type="text" class="form-control @error('comments') is-invalid @enderror" rows="5" name="comments">{{ old('comments') }}</textarea>
                                @error('comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type='submit' class='btn btn-sm btn-primary btn-static-200'>{{__('some.Submit')}}</button>
                        </div>
                    </div>
                </form>
                @endif
                
                <div class="p-2">
                    <div class="text-center mt-5">
                    <h4>Upcoming Meetings</h4>
                    </div>
                    <div class="col-md-10 offset-1">
                    @foreach($countries as $country)
                        <a style="color:#000" data-toggle="collapse" href="#collapsec{{$country->id}}" role="button" aria-expanded="false" aria-controls="collapsec{{$country->id}}">
                            <div>{{ $country->title }}
                                <i class="fa fa-chevron-down pull-right"></i>
                                <i class="fa fa-chevron-right pull-right"></i>
                            </div>
                        </a>
                    
                        <div class="collapse p-2" id="collapsec{{$country->id}}">
                        @foreach($country->cities as $city)
                            <a style="color:#000" data-toggle="collapse" href="#collapse{{$city->id}}" role="button" aria-expanded="false" aria-controls="collapse{{$city->id}}">
                                <div>{{$city->title}} 
                                    <i class="fa fa-chevron-down pull-right"></i>
                                    <i class="fa fa-chevron-right pull-right"></i>
                                </div>
                            </a>
                        
                            <div class="collapse p-2" id="collapse{{$city->id}}">
                            @foreach($city['dates']->sortKeys() as $date => $meetings)
                                <a style="color:#000" data-toggle="collapse" href="#collapse{{$city->id}}{{$date}}" role="button" aria-expanded="false" aria-controls="collapse{{$city->id}}{{$date}}">
                                    <div>
                                        {{ (new \Carbon\Carbon($date))->format('d/m/Y') }}
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </div>
                                </a>
                            
                                <div class="collapse p-2" id="collapse{{$city->id}}{{$date}}">
                                @foreach($meetings->sortBy('start_at') as $i=>$meeting)
                                <div class="mb-3">
                                    <div class="p-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        {{ $meeting->start_at->format('H:i') }}
                                                    </div>
                                                    <div class="col-md-8">
                                                    {{ $meeting->address }}
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-4">@lang('Topic:')</div>
                                                    <div class="col-md-8">
                                                    {{ $meeting->topic }}
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-4">@lang('Details:')</div>
                                                    <div class="col-md-8">
                                                    {!! nl2br(str_replace('  ', '&nbsp;&nbsp;', make_clickable_links(htmlspecialchars($meeting->comments)))) !!}
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-4">@lang('Organizer:')</div>
                                                    <div class="col-md-6">
                                                        <a href="{{ route('users.ideological_profile', $meeting->user->active_search_names->first()->hash)}}">
                                                        {{ $meeting->user->active_search_names->first() ? $meeting->user->active_search_names->first()->name : '-'}} 
                                                        </a>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        @if ((auth('admin')->user() ?: auth('web')->user())->can('delete', $meeting))
                                                        <form action="{{ route('meetings.delete',$meeting->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="_method" value="DELETE"/>
                                                            <button type='submit' class='btn btn-primary btn-sm'>{{__('some.Delete')}}</button>
                                                        </form>
                                                        @endif                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                            @endforeach
                            </div>
                        @endforeach               
                        </div>
                    @endforeach               
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

