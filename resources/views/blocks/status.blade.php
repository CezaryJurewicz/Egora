<div class="media">
    <span class="comment" id="comment-{{$comment->id}}"></span>
    
    <div class="media-body">
        
        <div class="message pb-4">
            <div id="comment{{ $comment->id }}" class="p-0">
                <div class="card-body">
                {!! filter_text($comment->message) !!}
                </div>
            </div>
            @if (auth('web')->check() && auth('web')->user()->can('update', $comment))
            <form id="edit{{ $comment->id }}" action="{{ route('users.status.update', $comment) }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="_method" value="PUT"/>

                <div class="textarea-mentions @error('message') is-invalid @enderror" placeholder="{{__('some.@<Search-Name>') }}" name="message" idName="message" value="{{ $comment->message }}"></div>
                @error('message')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="pt-2 text-left">
                    <button type="submit" class="btn btn-sm btn-primary col-md-2">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>     
            @endif
            
            <div class="row pl-2">
            <div class="col-md-2">
                <small>
                    @if (!isset($reply))
                        @if (auth('web')->check() && auth('web')->user()->can('comment', $comment))
                        <a href="#" onclick="$('#reply{{ $comment->id }}').toggle(); return false;" >Reply</a> 
                        @endif
                    @endif
                </small>
            </div>
            <div class="col-md-2">
                <small>
                    @if (auth('web')->check() && auth('web')->user()->can('update', $comment))
                        <a href="#" class="editbtn{{ $comment->id }}" onclick="$('#edit{{ $comment->id }}').toggle(); $('#comment{{ $comment->id }}').toggle(); $('.editbtn{{ $comment->id }}').toggle(); $([document.documentElement, document.body]).animate({ scrollTop: $('#edit{{ $comment->id }}').offset().top - 80 }, 100); return false;">{{__('Edit')}}</a> 
                        <a href="#" class="editbtn{{ $comment->id }}" onclick="$('#edit{{ $comment->id }}').toggle(); $('#comment{{ $comment->id }}').toggle(); $('.editbtn{{ $comment->id }}').toggle();  return false;" style="display:none">{{__('Cancel')}}</a> 
                    @endif
                </small>
            </div>
            <div class="col-md-3">
                <small>
                    @if (auth('web')->check() && auth('web')->user()->can('delete', $comment))
                        <a href="#" onclick="$('#remove{{ $comment->id }}').submit(); return false;" >{{__('Remove')}}</a> 
                        <form id="remove{{ $comment->id }}" action="{{ route('comments.delete', $comment) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                        </form>    
                    @endif
                </small>
            </div>
                
            </div>
            @if (!isset($reply))
                @if (auth('web')->check() && auth('web')->user()->can('comment', $comment))
                <form id="reply{{ $comment->id }}" method="POST" action="{{ route('users.status.reply', $comment) }}" style="display:none">
                    @csrf

                    <div class="textarea-mentions @error('message') is-invalid @enderror" placeholder="{{__('some.@<Search-Name>') }}" name="message" idName="message" value="{{ old('message') }}"></div>
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
            @endif
        </div>
    </div>
</div>  