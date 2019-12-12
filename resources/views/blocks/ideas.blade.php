@if($ideas->isNotEmpty()) 
                    <div class="card p-2">
                    @forelse($ideas as $i=>$idea)
                    <div class="mb-3">
                        <div class="p-2">
                            <div class="row small">
                                <div class="col-md-1">{{$i + $ideas->firstItem()}} </div>
                                <div class="col-md-4">{{$idea->nation->title}} </div>
                                <div class="col-md-1 text-center">
                                    @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                    @endif
                                </div>
                                <div class="col-md-1">
                                    @if($index == 'dominance')
                                    IDI Points: 
                                    @else
                                    Supporters:
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    @if($index == 'dominance')
                                    {{ number_format($idea->liked_users_sum) }}
                                    @else
                                    {{ number_format($idea->liked_users_count) }}
                                    @endif
                                </div>
                                <div class="col-md-1">
                                    @if($index == 'dominance')
                                    Supporters:
                                    @else
                                    IDI Points: 
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    @if($index == 'dominance')
                                    {{ number_format($idea->liked_users_count) }}
                                    @else
                                    {{ number_format($idea->liked_users_sum) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {!! shorten_text($idea->content) !!}
                            </div>
                        </div>
                    </div>
                    @empty
                        <!--<p>@lang('ideas.No ideas found')</p>-->
                    @endforelse
                    
                        {{  $ideas->appends(compact('search', 'relevance', 'unverified', 'nation'))->render() }}
                    </div>

@endif