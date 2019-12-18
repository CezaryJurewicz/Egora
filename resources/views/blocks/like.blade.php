                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('like', $idea) )
                    <form action="{{ route('ideas.like',[$idea->id]) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="position1" class="col-md-2 col-form-label">{{ __('Assign Position:') }}</label>

                            <div class="col-md-9">
                                <div class="form-group input-group row pl-3">
                                <select id="position1" type="text" class="mr-1 col-md-3 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                    <option></option>
                                    <optgroup label="Point Positions">
                                        @for($i=23; $i>0; $i--)
                                        <option @if(in_array($i+23, $numbered)) style="background-color: lightgray;" disabled @endif 
                                                 @if($current_idea_position && $current_idea_position == $i+23) selected @endif
                                                 value="{{$i+23}}">{{$i}}</option>
                                        @endfor
                                    </optgroup>
                                    <optgroup label="0-Point Positions">
                                        @for($i=23; $i>0; $i--)                                        
                                        <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                                 @if($current_idea_position && $current_idea_position == $i) selected @endif
                                                 value="{{$i}}">0 ({{$i}})</option>
                                        @endfor
                                    </optgroup>
                                </select>
                                
                                    <button type="submit" class="btn btn-primary col-md-3">
                                        {{ __('Save & Close') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
