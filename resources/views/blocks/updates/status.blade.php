                    <div id="cid{{$row->id}}" class="mb-3">
                        <div class="pb-2">
                            <div class="row">
                                @if (($row->updatable instanceof \App\Comment) && ($row->updatable->commentable instanceof \App\User))
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12">
                                        <b>
                                            <a style="color:#000;" href="{{ route('users.ideological_profile', $row->updatable->commentable->active_search_name_hash) }}">
                                            {{ $row->updatable->commentable->active_search_name ??  $row->updatable->commentable->id }} 
                                            </a>
                                        </b>
                                        posted a new status.
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            {{ $row->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-right">
                                    <a class="btn btn-primary btn-sm" href="{{ route('updates.redirect', [$row->id]) }}">{{ __('Open') }}</a>                                    
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {!! shorten_text_link($row->updatable->message) !!}
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="row">
                                <div class="col-3 text-left">
                                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('delete', $row) )
                                    <form action="{{ route('updates.delete', $row->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <input type="hidden" name="filter" value="{{$id}}"/>
                                        <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete')}}</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
