                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('like', $idea) )
                    <form action="{{ route('ideas.like',[$idea->id]) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-7 float-right">
                                <div class="float-right">
                                    <label class="mr-3 mt-1">Assign Point Position</label> 
                                </div>
                            </div>
                            <div class="input-group col-md-5">
                                <select id="position1" type="text" class="mr-1 col-md-6 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
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
                                <button type='submit' class='btn btn-primary col-md-6 mr-1'>{{__('Save and Close')}}</button>
                            </div>
                        </div>
                    </form>
                    @endif
