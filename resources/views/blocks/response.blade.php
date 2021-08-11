<div class="media">
    <span class="comment" id="comment-{{$comment->id}}"> &nbsp; </span>
    <div class="media-left">
        @if ($comment->user->image)
        <img src="{{ Storage::url($comment->user->image->filename) }}" class="media-object img-fluid img-thumbnail" sty1le="width:40px" alt=""> 
        @else
        <span class="media-object img-fluid img-thumbnail" style="padding-top:2px; font-size:23px; padding-left:28px;">&nbsp;</span>
        @endif
    </div>
    
    <div class="media-body">
        <h4 class="media-heading title">
            <a style="color:#000;" href="{{ route('users.ideological_profile', $comment->user->active_search_name_hash) }}">
            {{ $comment->user->active_search_name }}
            </a>
        </h4>
        <p class="message">
            <div id="comment{{ $comment->id }}" class="p-0">
                {!! make_clickable_links(nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), htmlspecialchars($comment->message)))) !!}
            </div>

            <div class="row">
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
                <form id="reply{{ $comment->id }}" method="POST" action="{{ route('comments.store.comment', $comment) }}" style="display:none">
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
            @endif
        </p>
    </div>
</div>  