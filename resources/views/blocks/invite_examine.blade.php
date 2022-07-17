                    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->can('invite_examine', [$idea, $notification]) )
                        <div class="row">
                            <div class="col-md-12">
                            @if(is_egora())
                            <b>Invite other philosophers to examine this idea:</b>
                            @elseif(is_egora('community'))
                            Invite other community members to examine this idea:
                            @endif
                            </div>
                        </div>
                    
                        <div class="row pt-2 pl-md-5 pb-4">
                            <div id="copyLink" value="{{ route('ideas.preview', base_convert($idea->id, 10, 36)) }}" class="col-md-8"></div>
                            <div class="share col-md-3 text-md-right" url="{{ route('ideas.preview', base_convert($idea->id, 10, 36)) }}"></div>
                        </div>
                        @foreach(Auth::guard('web')->user()->following->sortBy('active_search_name') as $u)
                            @if (Auth::guard('web')->user()->notifications_disabled_by->contains($u))
                                @continue
                            @endif
                        <div class="row-striped ">
                            @if (in_array($u->id, $user_notifications_ids) && !$u->liked_ideas->contains($idea))
                                <div class="row pt-1 pb-1 pl-5">
                                    <div class="col-md-6 align-self-center">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                            {{ $u->active_search_name }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        {{ __('Invited') }}
                                    </div>
                                    <div class="col-md-4 text-center">
                                        @if (is_egora())
                                        {{  $u->nation->title }}
                                        @elseif (is_egora('municipal'))
                                        {{ $u->municipality->title }} 
                                        @endif
                                    </div>
                                </div>
                            @elseif (is_egora('community') && !$u->communities->contains($idea->community))
                                <div class="row pt-1 pb-1 pl-md-5 pr-md-5">
                                    <div class="col-md-6 align-self-center">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                            {{ $u->active_search_name }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <div class="user-invite" action="{{ route('api.users.invite',[$u->id, $idea->id]) }}"></div>
                                    </div>
                                    
                                    <div class="col-md-4 text-center">
                                        {{ __('Not in this community') }} 
                                    </div>
                                </div>
                                <form action="{{ route('users.invite',[$u->id, $idea->id]) }}" method="POST" style="display: none;">
                                    @csrf

                                    <div class="row pt-1 pb-1 pl-5">
                                        <div class="col-6 align-self-center">
                                            <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                            {{ $u->active_search_name }}
                                            </a>
                                        </div>

                                        <div class="col-2  align-self-center text-center">
                                            <button type="submit" class="btn btn-sm btn-primary col-md-12">
                                                {{ __('Invite') }}
                                            </button>
                                        </div>

                                        <div class="col-4 text-center">
                                            {{ __('Not in this community') }} 
                                        </div>
                                    </div>
                                </form>
                            @elseif ($u->liked_ideas->contains($idea))
                                <div class="row pt-1 pb-1 pl-md-5">
                                    <div class="col-md-6 align-self-center">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                        {{ $u->active_search_name }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        @if(is_egora())
                                        {{ __('Supporter') }} 
                                        @else
                                        {{ __('Involved') }} 
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-4 text-center">
                                        @if (is_egora())
                                        {{  $u->nation->title }}
                                        @elseif (is_egora('municipal'))
                                        {{ $u->municipality->title }} 
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="row pt-1 pb-1 pl-md-5">
                                    <div class="col-md-6 align-self-center">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                            {{ $u->active_search_name }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <div class="user-invite" action="{{ route('api.users.invite',[$u->id, $idea->id]) }}"></div>
                                    </div>
                                    
                                    <div class="col-md-4 text-center">
                                        @if (is_egora())
                                        {{  $u->nation->title }}
                                        @elseif (is_egora('municipal'))
                                        {{ $u->municipality->title }} 
                                        @endif
                                    </div>
                                </div>
                            
                                <form action="{{ route('users.invite',[$u->id, $idea->id]) }}" method="POST" style="display: none;">
                                    @csrf
                                    <div class="row pt-1 pb-1 pl-5">
                                        <div class="col-md-6 align-self-center">
                                            <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                                {{ $u->active_search_name }}
                                            </a>
                                        </div>

                                        <div class="col-md-2 text-center">
                                        <button type="submit" class="btn btn-sm btn-primary col-md-12">
                                            {{ __('Invite') }}
                                        </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                        @endforeach
                    @endif
