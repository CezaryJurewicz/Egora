                            @include('blocks.user_image')                 
                            @if (!empty($user->role))
                            <div class="mt-3"><h4><i>{{ $user->role }}</i></h4></div>
                            @endif
                            
                            @if ( auth('admin')->check() && auth('admin')->user()->can('role', $user) )
                            <small>
                                <a href="#" onclick="$('#edit_role').toggle(); return false;">{{__('Edit role')}}</a> 
                            </small>
                            
                            <form id="edit_role" action="{{ route('users.update_role', $user) }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="_method" value="PUT"/>

                                <textarea id="role" class="form-control @error('role') is-invalid @enderror" name="role" rows="3">{{ $user->role }}</textarea>

                                <div class="pt-2 text-left">
                                    <button type="submit" class="btn btn-sm btn-primary col-md-5">
                                        {{ __('Update') }}
                                    </button>
      
                                    <a class="col-md-2 offset-md-1 p-0" href="{{ route('users.remove_role', $user) }}" 
                                               onclick="event.preventDefault(); document.getElementById('remove_role').submit();">
                                        {{ __('Remove') }}
                                    </a>
                                </div>
                            </form>     
                            
                            <form id="remove_role" action="{{ route('users.remove_role', $user) }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE"/>
                            </form>
                            
                            @endif

                            <div class="mt-3"><h4><i>{{ $user->user_type->subtitle }}</i></h4></div>
                            <!--<div class="mt-2">{{ $user->user_type->title }}</div>-->
                            
                            <div class="mt-2"><h4>@if (auth('admin')->check()) #{{ $user->id }}: @endif {{ $user->name }}</h4></div>
                            @if (auth('web')->check() && auth('web')->user()->can('update', $user))
                            <form action="{{ route('users.update_name', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                <button disabled class="btn btn-ssm btn-secondary col-md-3">Edit</button>
                            </form>
                            @endif
                            
                            <div class="mt-2">
                                @if(null !== $user->active_search_names->first()) <b>{{ $user->active_search_names->first()->name }}</b> @else - @endif
                                <br>
                                @if (auth('web')->user() && $user->id == auth('web')->user()->id && $user->active_search_names->first())
                                <!--<a class="btn btn-ssm btn-secondary col-md-3" href="{{ route('search_names.edit', $user->active_search_names->first()->id) }}">Edit</a>-->
                                @endif
                            </div>

                            @if (is_egora('community'))
                            <div class="mt-2">
                                @foreach($user->communities as $c)
                                @if(isset($community_id) && $community_id == $c->id)
                                {{$c->title}}<br/>
                                @else
                                <a href="{{ route('users.ideological_profile', [$user->active_search_names->first()->hash, 'community_id'=>$c->id ]) }}">{{$c->title}}</a><br/>
                                @endif
                                @endforeach
                            </div>
                            
                            @if ( (auth('admin')->user() ?: auth('web')->user())->can('communities', [App\User::class, $user->active_search_name_hash]) )
                            <div class="mt-2">
                                <a class="btn btn-secondary btn-sm btn-block" href="{{ route('users.communities', $user->active_search_name_hash) }}">Edit Communities</a>
                            </div>                            
                            @endif
                            @endif
                            
                            @if (is_egora('municipal'))
                            <div class="mt-2">
                                <b>{{ $user->municipality ? $user->municipality->title : '' }} </b>
                            </div>
                            @endif 
                            
                            @if (auth('web')->check() && auth('web')->user()->can('municipality_update', [App\User::class, $user->active_search_name_hash]) )
                            <div class="mt-2">
                                <a class="btn btn-secondary btn-sm btn-block" href="{{ route('users.municipality_update', $user->active_search_name_hash) }}">Edit Municipality</a>
                            </div>                            
                            @endif
                            
                            @if (is_egora())
                            <div class="mt-2">{{ $user->nation->title }}</div>
                            @if (auth('web')->check() && auth('web')->user()->can('update', $user))
                            <form action="{{ route('users.update_nation', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                <button disabled class="btn btn-ssm btn-secondary col-md-3">Edit</button>
                            </form>
                            @endif
                            <div class="mt-2">{{ $user->national_affiliations }}</div>
                            @endif
                            
                            @if($user->infoEmpty() && auth('web')->check() && auth('web')->user()->id == $user->id)
                            <div class="mt-2">Please provide your contact information so other philosophers can reach you to discuss ideas ;-)</div>
                            @else
                            <div class="mt-2">{{ $user->email_address }}</div>
                            <div class="mt-2">{{ $user->phone_number }}</div>
                            <div class="mt-2">{!! make_clickable_links(nl2br( htmlspecialchars($user->social_media_1))) !!}</div>
                            <div class="mt-2">{!! make_clickable_links(nl2br( htmlspecialchars($user->social_media_2))) !!}</div>
                            <div class="mt-2">{{ $user->messenger_1 }}</div>
                            <div class="mt-2">{{ $user->messenger_2 }}</div>
                            <div class="mt-2">{!! make_clickable_links(nl2br( htmlspecialchars($user->other_1))) !!}</div>
                            <div class="mt-2">{!! make_clickable_links(nl2br( htmlspecialchars($user->other_2))) !!}</div>
                            @endif

                            @if($user->office_hours && !empty(filter_office_hours_array($user->office_hours)))
                                <div class="mt-2"><b>{{ __('Citizen Office Hours') }}</b></div>
                                @foreach(filter_office_hours_array($user->office_hours) as $row)
                                <div class="mt-2"><div class="col row"><div class="col p-0">{{ $row['day'] }}</div><div class="col p-0"> {{ $row['from'] }}-{{ $row['to'] }}</div></div></div>
                                @endforeach
                                <div class="mt-2"><b>{{ __('Time Zone') }}</b></div>
                                <div class="mt-2">{{ $user->time_zone }}</div>
                                <div class="mt-2"><b>{{ __('Meeting Location / Link to Videoconference') }}</b></div>
                                <div class="mt-2">{!! make_clickable_links(nl2br( htmlspecialchars($user->meeting_location))) !!}</div>
                                <div class="mt-2"><b>{{ __('Link to Scheduling Calendar') }}</b></div>
                                <div class="mt-2">{!! make_clickable_links(nl2br( htmlspecialchars($user->calendar_link))) !!}</div>
                            @endif
                            
                            @if(auth('web')->check() && auth('web')->user()->id != $user->id)
                            <div class="mt-2">Online: {{ $user->last_online_at->diffForHumans() }}</div>
                            @endif
                            
                            @if (auth('web')->check() && auth('web')->user()->can('update', $user))
                            <form action="{{ route('users.update_contacts', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                <button disabled class="btn btn-ssm btn-secondary col-md-3"">Edit</button>
                            </form>
                            @endif
                            
                            @if (is_egora())
                            @if ((auth('web')->user()?:auth('admin')->user())->can('update', $user))
                            <div class="mt-2">
                                <a class="btn btn-sm btn-secondary btn-block" href="{{ route('users.edit', $user->id) }}">Edit</a>
                            </div>
                            @endif
                            
                            @if (auth('web')->user() && (auth('web')->user()->id == $user->id))
                            <div class="mt-2">
                                <a class="btn btn-sm btn-secondary btn-block" href="{{ route('users.subdivisions') }}">Administrative Subdivisions</a>
                            </div>
                            @endif 
                            
                            <div class="mt-2">
                            @include('blocks.ilp')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.candidate')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.petition')
                            </div>
                            @endif
                            
                            <div class="mt-2">
                            @include('blocks.follow')
                            </div>
                            
                            <div class="mt-2">
                            @include('blocks.leads')
                            </div>
                            <div class="mt-2">
                            @include('blocks.followers')
                            </div>
                            
                            @if (Auth::guard('web')->check() && Auth::guard('web')->user()->id !== $user->id)
                            <div class="mt-2">
                                @if (Auth::guard('web')->user()->notifications_disabled->contains($user))
                                    <form action="{{ route('notifications.enable', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <button type='submit' class='btn btn-sm btn-primary btn-block'>{{__('Enable Notifications')}}</button>
                                        </div>
                                    </form>                                    
                                @else
                                    <form action="{{ route('notifications.disable', $user->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <div class="input-group">
                                            <button type='submit' class='btn btn-sm btn-primary btn-block'>{{__('Disable Notifications')}}</button>
                                        </div>
                                    </form>    
                                @endif
                            </div>
                            @endif
                            
                            @if (is_egora())
                            <div class="mt-3">
                            @include('blocks.verification')
                            </div>
                            @endif
                            
                            <div class="mt-2">
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('disqualify_membership', $user) )
                                <a class="btn btn-black btn-sm btn-block" href="{{ route('users.disqualify_membership', $user->id ) }}">Disqualify Membership</a>
                                @endif
                            </div>

                            <div class="mt-2">
                                @if ((auth('admin')->user() ?: auth('web')->user())->can('reset', $user) )
                                <form action="{{ route('users.reset',[$user->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="POST"/>
                                    <div class="input-group">
                                        <button type='submit' class='btn btn-sm btn-ilp btn-block'>{{__('Restore Member')}}</button>
                                    </div>
                                </form>
                                @endif
                            </div>
                            
                            <div class="mt-2">
                                @if ( auth('admin')->check() && auth('admin')->user()->can('delete', $user) )
                                <form action="{{ route('users.delete',[$user->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE"/>
                                    <div class="input-group pt-5">
                                        <button type='submit' class='btn btn-sm btn-block' style="background-color: #ff00ff; color: #ffffff;">{{__('some.Delete')}}</button>
                                    </div>
                                </form>
                                @endif
                            </div>