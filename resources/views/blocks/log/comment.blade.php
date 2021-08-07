                    <div id="cid{{$row->id}}" class="mb-3">
                        <div class="pb-2">
                            <div class="row">
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12">
                                        <b>
                                            <a style="color:#000;" href="{{ route('users.ideological_profile', $row->sender->active_search_name_hash) }}">
                                            {{ $row->sender->active_search_name ??  $row->sender->id }} 
                                            </a>
                                        </b>
                                        {{ $row->message }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            {{ $row->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-right">
                                    @if ($row->comment->commentable && $row->comment->commentable->commentable instanceof \App\User)
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.about', [ $row->comment->commentable->commentable->active_search_names->first()->hash,'open'=>$row->comment->commentable->id]).'#comment-'.$row->comment->id }}">{{ __('Open') }}</a>                                    
                                    @elseif ($row->comment->commentable && $row->comment->commentable instanceof \App\User)
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.about', [ $row->comment->commentable->active_search_name_hash,'open'=>$row->comment->id]).'#comment-'.$row->comment->id }}">{{ __('Open') }}</a>                                    
                                    @elseif ($row->comment->is_response())
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', ['comments' => 1, $row->comment->commentable->commentable, 'comment_notification_id'=> $row->id, 'open'=>$row->comment->commentable->id]).'#comment-'.$row->comment->id }}">{{ __('Open') }}</a>
                                    @elseif (!is_null($row->comment->commentable))
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', ['comments' => 1, $row->comment->commentable, 'comment_notification_id'=> $row->id]).'#comment-'.$row->comment->id }}">{{ __('Open') }}</a>                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {!! make_clickable_links(shorten_text($row->comment->message)) !!}
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="row">
                                <div class="col-3 text-left">
                                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('delete', $row) )
                                    <form action="{{ route('comment_notifications.delete', $row->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete')}}</button>
                                    </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
