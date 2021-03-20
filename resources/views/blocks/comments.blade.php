                    @if ($item->comments->isNotEmpty() || (auth('web')->check() && auth('web')->user()->can('comment', $item)))
                    <div class="card-header">
                        <h5 class="mt-2">Comments</h5>
                    </div>
                    
                    @if (auth('web')->check() && auth('web')->user()->can('comment', $item))
                    <div class="card-body">
                    <a href="#" onclick="$('#storecomment').toggle(); return false;" >
                    <h5 class="pt-2">Add comment</h5>
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

                    <div class="card-body comments">
                    @foreach($item->comments as $comment)
                        @include('blocks.comment', ['comment' => $comment])                
                        @if ($comment->comments)
                            @foreach($comment->comments as $child)
                            <div class="reply">
                            @include('blocks.comment', ['comment' => $child, 'reply' => false])                
                            </div>
                            @endforeach
                        @endif
                    @endforeach
                    </div>
                    @endif