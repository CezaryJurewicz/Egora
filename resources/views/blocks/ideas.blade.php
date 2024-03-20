@if($ideas->isNotEmpty()) 
                    <div class="card p-2">
                    @forelse($ideas as $i=>$idea)
                    <div class="mb-3">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-12 col-sm-4 col-md-5">
                                    <div class="row">
                                        <div class="col-12">#{{$i + $ideas->firstItem()}} </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                        @if (is_egora())
                                            {{$idea->nation->title}}
                                        @elseif (is_egora('community'))
                                            {{$idea->community->title}}
                                        @elseif (is_egora('municipal'))
                                            {{$idea->municipality->title}}
                                        @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">Views: {{$idea->views_cnt}}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 col-md-2 text-center small pr-sm-4 pl-sm-4 pr-md-0 pl-md-0">
                                    @auth
                                    @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                    <a class="btn btn-sm btn-primary col-12" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                    <br/>
                                    <small>
                                    <a href="{{ route('ideas.view', [$idea->id, 'comments'] ).'#tabs' }}">{{ __('Comments:').' '.($idea->comments->count() + $idea->comments->reduce(function ($count, $comment) { return $count + $comment->comments->count(); }, 0)) }}</a>
                                    </small>
                                    <br/>
                                    <small>
                                        Votes: {{$idea->approval_ratings->count()}}
                                    </small>                                    
                                    @endif
                                    @endauth
                                    
                                    @guest
                                    <a class="btn btn-sm btn-primary col-12" href="{{ route('ideas.preview', preview_id($idea->id)) }}">{{ __('Open') }}</a>
                                    @endguest
                                </div>
                                <div class="offset-md-1 col-12 col-sm-4 pr-0">
                                    <div class="row">
                                        <div class="col-7">
                                        @if($index == 'dominance')
                                        IDI Points: 
                                        @else
                                        Supporters:
                                        @endif
                                        </div>
                                        <div class="col-5 col-sm-4">
                                        @if($index == 'dominance')
                                        {{ number_format($idea->liked_users_sum) }}
                                        @else
                                            @if (is_egora())
                                            {{ number_format($idea->liked_users_count) }}
                                            @else
                                            {{ number_format($idea->liked_users_count - $idea->moderators_count) }}
                                            @endif
                                        @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-7">
                                        @if($index == 'dominance')
                                        Supporters:
                                        @elseif (is_egora())
                                        IDI Points: 
                                        @else
                                        <br/>
                                        @endif
                                        </div>
                                        <div class="col-5 col-sm-4">
                                        @if($index == 'dominance')
                                        {{ number_format($idea->liked_users_count) }}
                                        @elseif (is_egora())
                                        {{ number_format($idea->liked_users_sum) }}
                                        @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-7">
                                        Rating:
                                        </div>
                                        <div class="col-5 col-sm-4">
                                        {{ number_format($idea->approval_ratings()->avg('score'), 3) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {!! shorten_text_link_characters($idea->content) !!}
                            </div>
                        </div>
                    </div>
                    @empty
                        <!--<p>@lang('ideas.No ideas found')</p>-->
                    @endforelse
                    
                        {{  $ideas->appends(compact('sort', 'search', 'relevance', 'unverified', 'nation', 'community', 'municipality'))->render() }}
                    </div>

@endif