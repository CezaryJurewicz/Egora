                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('invite_response', $notification) )
                    
                        @if($notification_response_sent)
                            Response sent.                            
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                <b>Other responses:</b>
                                </div>
                            </div>

                            <form action="{{ route('notifications.store') }}" method="POST">
                            @csrf

                            @if (isset($notification))
                            <input type="hidden" name="notification_id" value="{{ $notification->id }}"/>
                            @endif

                            <input type="hidden" name="idea_id" value="{{ $idea->id }}"/>
                            @foreach($presets as $preset)
                            <div class="row pt-2 pl-5">
                                <div class="col-md-9">
                                    <label for="preset_{{ $preset->id }}">
                                {{ $preset->title }}
                                    </label>
                                </div>

                                <div class="col-md-2 text-center">
                                    <input id="preset_{{ $preset->id }}" type="radio" name="preset_id" value="{{ $preset->id }}"/>
                                </div>
                            </div>
                            @endforeach

                            <div class="row pt-2 pl-5">
                                <div class="offset-4 col-md-3 mb-3">
                                    <button class='btn btn-primary btn-sm btn-block'>
                                        {{__('Submit')}}
                                    </button>
                                </div>
                            </div>

                            </form>
                        @endif
                        
                    @endif
