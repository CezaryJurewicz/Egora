                    <div class="card p-2">
                    @forelse($ideas as $i=>$idea)
                    <div class="mb-3">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-md-1">{{$i + $ideas->firstItem()}} </div>
                                <div class="col-md-3">{{$idea->nation->title}} </div>
                                <div class="col-md-2 text-center">
                                    @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    @if($index == 'dominance')
                                    IDI Points: 
                                    @else
                                    Supporters:
                                    @endif
                                </div>
                                <div class="col-md-1">
                                    @if($index == 'dominance')
                                    {{ $idea->liked_users_sum }}
                                    @else
                                    {{ $idea->liked_users_count }}
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    @if($index == 'dominance')
                                    Supporters:
                                    @else
                                    IDI Points: 
                                    @endif
                                </div>
                                <div class="col-md-1">
                                    @if($index == 'dominance')
                                    {{ $idea->liked_users_count }}
                                    @else
                                    {{ $idea->liked_users_sum }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {!! strip_tags(nl2br(str_replace(' ', '&nbsp;', implode(' ', array_slice(explode(' ', $idea->content), 0, 50)))), '<br><p><b><i><li><ul><ol>') !!} ...
                            </div>
                        </div>
                    </div>
                    @empty
                        <!--<p>@lang('ideas.No ideas found')</p>-->
                    @endforelse
                    
                    @if($ideas->isNotEmpty())       
                        {{  $ideas->appends(compact('search', 'relevance', 'unverified', 'nation'))->render() }}
                    @endif
                    </div>