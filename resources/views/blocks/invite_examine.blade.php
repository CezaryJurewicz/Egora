                    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->can('invite_examine', [$idea, $notification]) )
                        <div class="row">
                            <div class="col-md-12">
                            <b>Invite other philosophers to examine this idea:</b>
                            </div>
                        </div>
                    
                        <div class="row pt-2 pl-5 pb-4">
                            <div class="col-md-6">
                            Invitation link to share outside of Egora
                            </div>

                            <div id="copyLink" class="col-md-2"></div>
                        </div>
                    
                        @foreach(Auth::guard('web')->user()->following as $u)
                            @if ($u->liked_ideas->contains($idea))
                                <div class="row pt-2 pl-5">
                                    <div class="col-md-6">

                                    {{ $u->active_search_names->first()->name ?? $u->id }}
                                    </div>

                                    <div class="col-md-2 text-center">
                                        {{ __('Supporter') }}
                                    </div>
                                </div>
                            @elseif (Auth::guard('web')->user()->user_notifications->first(function ($v, $k) use ($u, $idea) {
                                    return $v->id == $u->id && $v->pivot->idea_id == $idea->id;
                                }))
                                <div class="row pt-2 pl-5">
                                    <div class="col-md-6">

                                    {{ $u->active_search_names->first()->name ?? $u->id }}
                                    </div>

                                    <div class="col-md-2 text-center">
                                        {{ __('Invited') }}
                                    </div>
                                </div>
                            @else
                            <form action="{{ route('users.invite',[$u->id]) }}" method="POST">
                                @csrf
                                <div class="row pt-2 pl-5">
                                    <div class="col-md-6">

                                    {{ $u->active_search_names->first()->name ?? $u->id }}
                                    </div>

                                    <input type="hidden" name="idea_id" value="{{ $idea->id }}"/>
                                    <button type="submit" class="btn btn-sm btn-primary col-md-2">
                                        {{ __('Invite') }}
                                    </button>
                                </div>
                            </form>
                            @endif
                        @endforeach
                    @endif
