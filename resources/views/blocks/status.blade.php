<div class="media">
    <span class="comment" id="comment-{{$comment->id}}"></span>
    
    <div class="media-body">
        
        <div class="message pb-4">
            <div id="comment{{ $comment->id }}" class="p-0">
                <div class="card-header">
                {!! nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), make_clickable_links(htmlspecialchars($comment->message)))) !!}
                </div>
            </div>
            @if (auth('web')->check() && auth('web')->user()->can('update', $comment))
            <form id="edit{{ $comment->id }}" action="{{ route('users.status.update', $comment) }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="_method" value="PUT"/>

                <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="5" placeholder="{{__('some.@<Search-Name>') }}" required>{{ $comment->message }}</textarea>
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
            <div class="col-md-1">
                <small>
                    @if (auth('web')->check() && auth('web')->user()->can('update', $comment))
                        <a href="#" class="editbtn{{ $comment->id }}" onclick="$('#edit{{ $comment->id }}').toggle(); $('#comment{{ $comment->id }}').toggle(); $('.editbtn{{ $comment->id }}').toggle();  return false;">{{__('Edit')}}</a> 
                        <a href="#" class="editbtn{{ $comment->id }}" onclick="$('#edit{{ $comment->id }}').toggle(); $('#comment{{ $comment->id }}').toggle(); $('.editbtn{{ $comment->id }}').toggle();  return false;" style="display:none">{{__('Cancel')}}</a> 
                    @endif
                </small>
            </div>
            <div class="col-md-4">
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

                    <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="5" placeholder="{{__('some.@<Search-Name>') }}" required>{{ old('message') }}</textarea>
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