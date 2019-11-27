                    @forelse($ideas as $idea)
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="offset-1 col-md-2">{{$idea->nation->title}} </div>
                                <div class="col-md-2">
                                    @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                    <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">{{ __('Open') }}</a>
                                    @endif
                                </div>
                                <div class="offset-3 col-md-2">
                                    @if($index == 'dominance')
                                    IDI Points {{ $idea->liked_users_sum }}
                                    @else
                                    Supporters {{ $idea->liked_users_count }}
                                    @endif
                                    
                                
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 50)) }} ...
                        </div>
                    </div>
                    @empty
                        <p>@lang('ideas.No ideas found')</p>
                    @endforelse
