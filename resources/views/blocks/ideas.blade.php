@if($ideas->isNotEmpty()) 
                    <div class="card p-2">
                    @forelse($ideas as $i=>$idea)
                    <div class="mb-3">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-8 col-sm-5">
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
                                </div>
                                <div class="col-2 text-center">
                                    @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                    @endif
                                </div>
                                <div class="offset-md-1 col-12 col-sm-4 pr-0">
                                    <div class="row">
                                        <div class="col-7 col-sm-6">
                                        @if($index == 'dominance')
                                        IDI Points: 
                                        @else
                                        Supporters:
                                        @endif
                                        </div>
                                        <div class="col-5">
                                        @if($index == 'dominance')
                                        {{ number_format($idea->liked_users_sum) }}
                                        @else
                                        {{ number_format($idea->liked_users_count) }}
                                        @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-7 col-sm-6">
                                        @if($index == 'dominance')
                                        Supporters:
                                        @elseif (is_egora())
                                        IDI Points: 
                                        @endif
                                        </div>
                                        <div class="col-5">
                                        @if($index == 'dominance')
                                        {{ number_format($idea->liked_users_count) }}
                                        @elseif (is_egora())
                                        {{ number_format($idea->liked_users_sum) }}
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {!! make_clickable_links(shorten_text($idea->content)) !!}
                            </div>
                        </div>
                    </div>
                    @empty
                        <!--<p>@lang('ideas.No ideas found')</p>-->
                    @endforelse
                    
                        {{  $ideas->appends(compact('sort', 'search', 'relevance', 'unverified', 'nation', 'community', 'municipality'))->render() }}
                    </div>

@endif