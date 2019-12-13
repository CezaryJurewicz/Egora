                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('like', $idea) )
                    <form class="col-md-8" action="{{ route('ideas.like',[$idea->id]) }}" method="POST">
                        @csrf

                        <div class="input-group">
                            <label class="mr-3">Assign Position</label>

                            
                            <select id="position1" type="text" class="mr-1 col-md-3 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                <option></option>
                                <optgroup label="Point positions">
                                    @for($i=23; $i>0; $i--)
                                    <option @if(in_array($i+23, $numbered)) style="background-color: lightgray;" disabled @endif 
                                             @if($current_idea_position && $current_idea_position == $i+23) selected @endif
                                             value="{{$i+23}}">{{$i}}</option>
                                    @endfor
                                </optgroup>
                                <optgroup label="Non-Point positions">
                                    @for($i=23; $i>0; $i--)                                        
                                    <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                             @if($current_idea_position && $current_idea_position == $i) selected @endif
                                             value="{{$i}}">0 ({{$i}})</option>
                                    @endfor
                                </optgroup>
                            </select>

                            <button type='submit' class='btn btn-primary'>{{__('Save and Close')}}</button>
                        </div>
                    </form>
                    @endif
