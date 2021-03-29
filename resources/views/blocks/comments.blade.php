                    @if ($item->comments->isNotEmpty() || (auth('web')->check() && auth('web')->user()->can('comment', $item)))
                    
                    @if (auth('web')->check() && auth('web')->user()->can('comment', $item))
                    <div class="pl-1 pt-3 pb-3">
                    <a href="#" onclick="$('#storecomment').toggle(); return false;" >
                    <h5 class="pt-0">Add comment</h5>
                    </a>
                    <form id="storecomment" method="POST" action="{{ route('ideas.store.comment', $item) }}" style="display:none">
                        @csrf
                        <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="5" required>{{ old('message') }}</textarea>
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
                    </div>
                    @endif
                    
                    <div class="mb-2">
                    Order:                    
                    @if ($order == 'asc')
                    &#x25B2; <a href="{{ route('ideas.view', array_merge(['idea'=>$item->id, 'order' => 'desc'], compact('notification_id'))).'#my-tab-content' }}">new-old</a> 
                    @elseif ($order == 'desc')
                    &#x25BC; <a href="{{ route('ideas.view', array_merge(['idea'=>$item->id, 'order' => 'asc'], compact('notification_id'))).'#my-tab-content' }}">old-new</a> 
                    @endif
                    </div>
                    
                    <div class="comments">
                    @foreach($comments as $comment)
                        @include('blocks.comment', ['comment' => $comment])                
                        @if ($comment->comments)
                            @foreach($comment->comments as $child)
                            <div class="reply">
                            @include('blocks.comment', ['comment' => $child, 'reply' => false])                
                            </div>
                            @endforeach
                        @endif
                    @endforeach
                    
                    {{ $comments->appends(compact('notification_id', 'order'))->fragment('my-tab-content')->links() }}
                    </div>
                    @endif
