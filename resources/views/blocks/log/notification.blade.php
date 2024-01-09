                    <div id="nid{{$row->id}}" class="mb-3">
                        <div class="pb-2">
                            <div class="row">
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12">
                                        <b>
                                            <a style="color:#000;" href="{{ route('users.ideological_profile', $row->sender->active_search_name_hash) }}">
                                            {{ $row->sender->active_search_names->first()->name ??  $row->sender->id }} 
                                            </a>
                                        </b>
                                        @if ($row->invite)
                                            {{ __('invited you to examine an idea.') }}
                                        @elseif (!$row->sender->liked_ideas->contains($row->idea) && !$row->response)
                                            {{ __('removed their support from this idea.') }}
                                        @elseif ($row->sender->liked_ideas->contains($row->idea) && !$row->response)
                                            {{ __('supported your idea!') }}
                                        @elseif ($row->response)
                                            responded to your invitation: {{ $row->notification_preset->title }}
                                        @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            {{ $row->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-right">
                                    @if ($row->invite)
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', [$row->idea->id,'notification_id'=>$row->id]) }}">{{ __('Open') }}</a>
                                    @else
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', [$row->idea->id, 'invitation_response_notification_id' => $row->id]) }}">{{ __('Open') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!$row->invite)
                        <div class="card">
                            <div class="card-body">
                                {!! shorten_text_link($row->idea->content) !!}
                            </div>
                        </div>
                        @endif
                        
                        @if ($row->notification_response_sent)
                        <div class="card">
                            <div class="card-body">
                                Response sent
                            </div>
                        </div>
                        @endif

                        <div class="pt-2">
                            <div class="row">
                                <div class="col-3 text-left">
                                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('delete', $row) )
                                    <form action="{{ route('notifications.delete', $row->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete')}}</button>
                                    </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>