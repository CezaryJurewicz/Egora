                    @if ($user->comments->isNotEmpty() || (auth('web')->check() && auth('web')->user()->can('status', $item)))
                    @if (auth('web')->check() && auth('web')->user()->can('status', $item))
                    <div class="row pt-3 pl-1">
                        <div class="col-md-4">
                            <a href="#" onclick="$('#storestatus').toggle(); return false;" >
                            <h5 class="pt-0">Add Status</h5>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="row pb-4 pl-1">
                        <div class="col-md-12">
                            @if (auth('web')->check() && auth('web')->user()->can('status', $item))
                            <form id="storestatus" method="POST" action="{{ route('users.status', $item) }}" style="display:none">
                                @csrf
                                <div class="textarea-mentions @error('message') is-invalid @enderror" placeholder="{{__('Use {Search-Name} to link other philosophers in your status.') }}" name="message" idName="message" value="{{ old('message') }}"></div>
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
                    @foreach($statuses as $status)
                        <div class="card mb-3">
                        @include('blocks.status', ['comment' => $status])                
                        @if ($status->comments->isNotEmpty())
                        <div style="padding-left:48px; margin-top: -20px; padding-bottom: 10px;">
                            <small>
                            <a href="#" onclick="$('#responses{{ $status->id }}').toggle(); return false;" >Responses ({{ $status->comments->count() }})</a>
                            </small>
                        </div>
                        <div id="responses{{ $status->id }}" @if( !isset($open) || is_null($open) || (isset($open) && ($open != $status->id))) style="display:none" @endif>
                            @foreach($status->comments as $child)
                                <div class="reply">
                                @include('blocks.response', ['comment' => $child, 'reply' => false])                
                                </div>
                            @endforeach
                        </div>
                        @endif
                        </div>
                    @endforeach
                    
                    {{ $statuses->links() }}
                    </div>
                    @endif
