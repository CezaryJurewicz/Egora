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
                    
                        <div class="row pt-2 pl-5 pb-4">
                            <div class="col-md-6">
                            External invitation link
                            </div>

                            <div id="copyLink" class="col-md-2"></div>
                        </div>
                    
                        @foreach(Auth::guard('web')->user()->following->sortBy('active_search_name') as $u)
                        <div class="row-striped ">
                            @if (is_egora('community') && !$u->communities->contains($idea->community))
                                <div class="row pt-1 pb-1 pl-5">
                                    <div class="col-md-6">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                        {{ $u->active_search_names->first()->name ?? $u->id }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        {{ __('Not in this Community') }} 
                                    </div>
                                </div>
                            @elseif ($u->liked_ideas->contains($idea))
                                <div class="row pt-1 pb-1 pl-5">
                                    <div class="col-md-6">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                        {{ $u->active_search_names->first()->name ?? $u->id }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        @if(is_egora())
                                        {{ __('Supporter') }} 
                                        @elseif(is_egora('community'))
                                        {{ __('Involved') }} 
                                        @endif
                                    </div>
                                </div>
                            @elseif (Auth::guard('web')->user()->user_notifications->first(function ($v, $k) use ($u, $idea) {
                                    return $v->id == $u->id && $v->pivot->idea_id == $idea->id;
                                }))
                                <div class="row pt-1 pb-1 pl-5">
                                    <div class="col-md-6">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                            {{ $u->active_search_names->first()->name ?? $u->id }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        {{ __('Invited') }}
                                    </div>
                                </div>
                            @else
                            <form action="{{ route('users.invite',[$u->id, $idea->id]) }}" method="POST">
                                @csrf
                                <div class="row pt-1 pb-1 pl-5">
                                    <div class="col-md-6">
                                        <a style="color:#000;" href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                                            {{ $u->active_search_names->first()->name ?? $u->id }}
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
