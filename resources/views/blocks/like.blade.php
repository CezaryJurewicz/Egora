                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('like', $idea) )
                    
                    <div class="card-footer pt-4 pb-4">
                        @if(isset($current_idea_position) && !is_null($current_idea_point_position))Current Position in my IP:<span  class="font-weight-bold">&nbsp;&nbsp;&nbsp;{{ str_pad($current_idea_point_position, 20, ' ', STR_PAD_LEFT) }}</span> @endif
                    </div>
                    
                    <div class="card-body">
                    <form action="{{ route('ideas.like',[$idea->id]) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="position1" class="col-md-2 col-form-label">{{ __('Assign Position:') }}</label>

                            <select id="position1" type="text" class="col-md-2 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                <option></option>
                                @if (is_null($idea->community) && is_null($idea->municipality))
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

                                <optgroup label="Egora Ideas">
                                @foreach([-1=>'E',-2=>'G',-3=>'O',-4=>'R',-5=>'A'] as $i=>$v)                                        
                                <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                        @if($current_idea_position && $current_idea_position == $i) selected @endif
                                        value="{{$i}}">{{$v}}</option>
                                @endforeach
                                </optgroup>

                                @else
                                <optgroup label="Supporting [+]">
                                    @for($i=23; $i>0; $i--)                                        
                                    <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                             @if($current_idea_position && $current_idea_position == $i) selected @endif
                                             value="{{$i}}">({{$i}})</option>
                                    @endfor
                                </optgroup>
                                <optgroup label="Moderating [-]">
                                @foreach([-1=>'N',-2=>'O',-3=>'H',-4=>'A',-5=>'T', -6=>'E'] as $i=>$v)                                        
                                <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                        @if($current_idea_position && $current_idea_position == $i) selected @endif
                                        value="{{$i}}">{{$v}}</option>
                                @endforeach
                                </optgroup>
                                @endif
                            </select>
                            
                            @if (isset($notification))
                            <input type="hidden" name="notification_id" value="{{ $notification->id }}"/>
                            @endif
                            
                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-primary col-md-6">
                                    {{ __('Save & Close') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                    @endif
