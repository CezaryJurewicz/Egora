                    <form class="col-md-3" action="{{ route('ideas.like',[$idea->id]) }}" method="POST">
                        @csrf

                        <div class="input-group">
                            <select id="position1" type="text" class="form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                <option></option>
                                @for($i=1; $i<24; $i++)
                                <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif @if(old('position1') && old('position1')== $i) selected @endif value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            
                            <select id="position2" type="text" class="form-control @error('position2') is-invalid @enderror" name="position2" value="{{ old('position2') }}">
                                <option></option>
                                @for($i=1; $i<24; $i++)
                                <option @if(in_array($i, $zeros)) style="background-color: lightgray;" disabled @endif value="0">0</option>
                                @endfor
                            </select>

                            <button type='submit' class='btn btn-danger'>{{__('Like')}}</button>
                        </div>
                    </form>
