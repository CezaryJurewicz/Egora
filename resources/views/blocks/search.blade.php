                <form action="{{ route(Route::current()->getName()) }}" method="POST">
                <div class="form-group row">
                    <div class="col-md-2">
                        <a class="btn btn-sm btn-primary" href="{{ route('ideas.create') }}">Create Idea</a>
                    </div>
                    <label for="search" class="col-md-1 col-form-label text-md-right">{{ __('Search') }}</label>

                        @csrf
                        <div class="col-md-6">
                            <input id="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search')?: $search }}" autofocus>

                            @error('search')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-1">
                            <div class="input-group">
                                <button type='submit' class='btn btn-sm btn-primary'>{{__('some.Search')}}</button>
                            </div>
                        </div>
                </div>
                        <div class="form-group row">
                            <label for="nation" class="col-md-2 col-form-label text-md-right">{{ __('Nation') }}</label>

                            <div class="col-md-8">
                                <select id="nation" type="text" class="form-control @error('nation') is-invalid @enderror" name="nation" value="{{ old('nation') }}" autocomplete="nation" >
                                <option></option>
                                @foreach($nations as $nation)
                                <option @if((old('nation') && old('nation')==$nation->id) || ($nation_id && $nation_id==$nation->id))  selected @endif value="{{$nation->id}}">{{$nation->title}}</option>
                                @endforeach
                                </select>
                                
                                @error('nation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                    
                    
                </form>