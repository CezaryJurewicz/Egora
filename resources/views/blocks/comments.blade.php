                    @if ($item->comments->isNotEmpty() || (auth('web')->check() && auth('web')->user()->can('comment', $item)))
                    <div class="row pt-3 pl-1">
                        
                        <div class="col-md-4">
                            @if (auth('web')->check() && auth('web')->user()->can('comment', $item))
                            <a href="#" onclick="$('#storecomment').toggle(); return false;" >
                            <h5 class="pt-0">Add comment</h5>
                            </a>
                            @endif
                        </div>

                        <div class="offset-3 col-md-3 text-right">
                            Filter:                    
                            @if ($filter == 'my')
                            <a href="{{ route('ideas.view', array_merge(['idea'=>$item->id], compact('notification_id'))).'#my-tab-content' }}">all comments</a> 
                            @else
                            <a href="{{ route('ideas.view', array_merge(['idea'=>$item->id, 'filter' => 'my'], compact('notification_id'))).'#my-tab-content' }}">my comments</a> 
                            @endif
                        </div>
                        <div class="col-md-2 mb-2 text-right">
                            Order:                    
                            @if ($order == 'asc')
                            &#x25B2; <a href="{{ route('ideas.view', array_merge(['idea'=>$item->id, 'order' => 'desc'], compact('notification_id'))).'#my-tab-content' }}">new-old</a> 
                            @elseif ($order == 'desc')
                            &#x25BC; <a href="{{ route('ideas.view', array_merge(['idea'=>$item->id, 'order' => 'asc'], compact('notification_id'))).'#my-tab-content' }}">old-new</a> 
                            @endif
                        </div>
                    </div>
                    
                    <div class="row pb-4 pl-1">
                        <div class="col-md-12">
                            @if (auth('web')->check() && auth('web')->user()->can('comment', $item))
                            <form id="storecomment" method="POST" action="{{ route('ideas.store.comment', $item) }}" style="display:none">
                                @csrf
                                <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="5" placeholder="{{__('some.@<Search Name>') }}" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="pt-2 text-left">
                                    <button type="submit" class="btn btn-sm btn-primary col-md-2">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                         
                    <div class="comments">
                    @foreach($comments as $comment)
                        <div>
                        @include('blocks.comment', ['comment' => $comment])                
                        @if ($comment->comments->isNotEmpty())
                        <div style="padding-left:44px; margin-top: -20px; padding-bottom: 10px;">
                            <small>
                            <a href="#" onclick="$('#responses{{ $comment->id }}').toggle(); return false;" >Responses ({{ $comment->comments->count() }})</a>
                            </small>
                        </div>
                        <div id="responses{{ $comment->id }}" @if(is_null($open) || (isset($open) && ($open != $comment->id))) style="display:none" @endif>
                            @foreach($comment->comments as $child)
                                <div class="reply">
                                @include('blocks.comment', ['comment' => $child, 'reply' => false])                
                                </div>
                            @endforeach
                        </div>
                        @endif
                        </div>
                    @endforeach
                    
                    {{ $comments->appends(compact('notification_id', 'order'))->fragment('my-tab-content')->links() }}
                    </div>
                    @endif
