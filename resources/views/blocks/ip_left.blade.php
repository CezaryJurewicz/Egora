                            @include('blocks.user_image')                 
                            
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
                            
                            <div class="mt-2">{{ $user->contacts }}</div>
                            @if (auth('web')->check() && auth('web')->user()->can('update', $user))
                            <form action="{{ route('users.update_contacts', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                <button disabled class="btn btn-ssm btn-secondary col-md-3"">Edit</button>
                            </form>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('update', $user))
                            <div class="mt-2">
                                <a class="btn btn-sm btn-secondary btn-block" href="{{ route('users.edit', $user->id) }}">Edit</a>
                            </div>
                            @endif

                            <div class="mt-2">
                            @include('blocks.ilp')
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
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('cancel_guardianship', $user) )
                                <a class="btn btn-black btn-sm btn-block" href="{{ route('users.cancel_guardianship', $user->id ) }}">Cancel Guardianship</a>
                                @endif
                            </div>
                            
                            <div class="mt-2">
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('allow_guardianship', $user) )
                                <a class="btn btn-black btn-sm btn-block" href="{{ route('users.allow_guardianship', $user->id ) }}">Allow Guardianship</a>
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