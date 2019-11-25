                    @if(Auth::guard('web')->check())
                    <form class="col-md-8" action="{{ route('ideas.like',[$idea->id]) }}" method="POST">
                        @csrf

                        <div class="input-group">
                            <label class="mr-3">Assign Point Position</label>
                            <select id="position1" type="text" class="mr-1 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                <option></option>
                                @for($i=23; $i>0; $i--)
                                <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                        @if(old('position1') && old('position1')== $i) selected @endif 
                                        @if($current_idea_position && $current_idea_position == $i) selected @endif 
                                        value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            
                            <select id="position2" type="text" class="mr-3 form-control @error('position2') is-invalid @enderror" name="position2" value="{{ old('position2') }}">
                                <option></option>
                                <option @if(in_array(1, $zeros)) style="background-color: lightgray;" disabled @endif 
                                        @if($current_idea_position === 0) selected @endif 
                                        value="0">1</option>
                                @for($i=2; $i<24; $i++)
                                <option @if(in_array($i, $zeros)) style="background-color: lightgray;" disabled @endif value="0">{{$i}}</option>
                                @endfor
                            </select>

                            <button type='submit' class='btn btn-primary'>{{__('Save and Close')}}</button>
                        </div>
                    </form>
                    @endif
