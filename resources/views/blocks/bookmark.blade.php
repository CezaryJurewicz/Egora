                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('bookmark', $idea) )
                    <div class="card-body">
                    <form action="{{ route('ideas.bookmark',[$idea->id]) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3 text-center">
                                <button type="submit" class="btn btn-primary col-md-auto">
                                    @if($idea->is_bookmarked(Auth::guard('web')->user()))
                                    {{ __('Unbookmark') }}
                                    @else
                                    {{ __('Bookmark') }}
                                    @endif                                    
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                    @endif
