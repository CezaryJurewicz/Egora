                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('invite_response', $notification) )
                    
                    <div class="card">
                        @if($notification_response_sent)
                            <div class="card-header">
                            </div>                
                            <div class="card-body">
                            Response sent.                            
                            </div>
                        @else
                            <div class="card-header">
                                <h5 class="mt-2">Other responses:</h5>
                            </div>                
                            <div class="card-body">
<!--                                <div class="row">
                                    <div class="col-md-12">
                                    <b>Other responses:</b>
                                    </div>
                                </div>-->

                                <form action="{{ route('notifications.store') }}" method="POST">
                                @csrf

                                @if (isset($notification))
                                <input type="hidden" name="notification_id" value="{{ $notification->id }}"/>
                                @endif

                                <input type="hidden" name="idea_id" value="{{ $idea->id }}"/>
                                @foreach($presets as $preset)
                                <div class="row pt-2 pl-5">
                                    <div class="col-md-9 col-8">
                                        <label for="preset_{{ $preset->id }}">
                                    {{ $preset->title }}
                                        </label>
                                    </div>

                                    <div class="col-md-2 col-2 align-self-center">
                                        <input id="preset_{{ $preset->id }}" type="radio" name="preset_id" value="{{ $preset->id }}"/>
                                    </div>
                                </div>
                                @endforeach

                                <div class="row pt-2 pl-md-5">
                                    <div class="offset-md-4 col-md-3 mb-3">
                                        <button class='btn btn-primary btn-sm btn-block'>
                                            {{__('Send')}}
                                        </button>
                                    </div>
                                </div>

                                </form>
                            </div>
                            @endif
                    </div>
                        
                    @endif
