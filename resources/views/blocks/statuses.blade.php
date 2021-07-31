                    @if ($user->comments->isNotEmpty() || (auth('web')->check() && auth('web')->user()->can('status', $item)))
                    @if (auth('web')->check() && auth('web')->user()->can('status', $item))
                    <div class="row pt-3 pl-1">
                        <div class="col-md-4">
                            <a href="#" onclick="$('#storestatus').toggle(); return false;" >
                            <h5 class="pt-0">Add status</h5>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="row pb-4 pl-1">
                        <div class="col-md-12">
                            @if (auth('web')->check() && auth('web')->user()->can('status', $item))
                            <form id="storestatus" method="POST" action="{{ route('users.status', $item) }}" style="display:none">
                                @csrf
                                <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="5" placeholder="{{__('Use {Search Name} to link other philosophers in your status.') }}" required>{{ old('message') }}</textarea>
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
                        <div>
                        @include('blocks.status', ['comment' => $status])                
                        @if ($status->comments->isNotEmpty())
                        <div style="padding-left:44px; margin-top: -20px; padding-bottom: 10px;">
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
