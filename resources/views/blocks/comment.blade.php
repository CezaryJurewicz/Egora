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
                {!! nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), make_clickable_links(htmlspecialchars($comment->message)))) !!}
            </div>
            @if (auth('web')->check() && auth('web')->user()->can('update', $comment))
            <form id="edit{{ $comment->id }}" action="{{ route('comments.update', $comment) }}" method="POST" style="display:none">
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
            
            <br>
            <div class="row">
            <div class="col-md-2 mt-2">
                <small>
                    @if (!isset($reply))
                        @if (auth('web')->check() && auth('web')->user()->can('comment', $comment))
                        <a href="#" onclick="$('#reply{{ $comment->id }}').toggle(); return false;" >Reply</a> 
                        @endif
                    @endif
                </small>
            </div>
            <div class="col-md-1 mt-2">
                <small>
                    @if (auth('web')->check() && auth('web')->user()->can('update', $comment))
                        <a href="#" class="editbtn{{ $comment->id }}" onclick="$('#edit{{ $comment->id }}').toggle(); $('#comment{{ $comment->id }}').toggle(); $('.editbtn{{ $comment->id }}').toggle();  return false;">{{__('Edit')}}</a> 
                        <a href="#" class="editbtn{{ $comment->id }}" onclick="$('#edit{{ $comment->id }}').toggle(); $('#comment{{ $comment->id }}').toggle(); $('.editbtn{{ $comment->id }}').toggle();  return false;" style="display:none">{{__('Cancel')}}</a> 
                    @endif
                </small>
            </div>
            <div class="col-md-4 mt-2">
                <small>
                    @if (auth('web')->check() && auth('web')->user()->can('delete', $comment))
                        <a href="#" onclick="$('#remove{{ $comment->id }}').submit(); return false;" >{{__('Remove my comment')}}</a> 
                        <form id="remove{{ $comment->id }}" action="{{ route('comments.delete', $comment) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                        </form>    
                    @endif
                </small>
            </div>
                
            <div class="col-md-5 text-right">
                <small>
                @if (auth('web')->check() && auth('web')->user()->can('moderate', $comment))
                    @if ($vote = $comment->votes()->where('user_id',auth('web')->user()->id)->first()) 
                        <span class="moderate" comment="comment-{{$comment->id}}"
                              vote= "{{$vote->pivot->vote}}"
                              action_keep="{{ route('comments.moderate', [$comment, 'action'=> 'keep']) }}" 
                              action_delete="{{ route('comments.moderate', [$comment, 'action' => 'delete']) }}">{{ $comment->score }}</span>            
                    @else
                        <span class="moderate" comment="comment-{{$comment->id}}"
                              vote="0"
                              action_keep="{{ route('comments.moderate', [$comment, 'action'=> 'keep']) }}" 
                              action_delete="{{ route('comments.moderate', [$comment, 'action' => 'delete']) }}">{{ $comment->score }}</span>            
                    @endif
                @else
                    {{ $comment->score }}
                @endif
                </small>
            </div>
            
            </div>
            @if (!isset($reply))
                @if (auth('web')->check() && auth('web')->user()->can('comment', $comment))
                <form id="reply{{ $comment->id }}" method="POST" action="{{ route('comments.store.comment', $comment) }}" style="display:none">
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
        </p>
    </div>
</div>  