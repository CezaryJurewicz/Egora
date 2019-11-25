                <form action="{{ route(Route::current()->getName()) }}" method="POST">
                    <div class="form-group row">
                        <label for="relevance" class="offset-2 col-md-1 col-form-label text-md-right">{{ __('Relevance:') }}</label>

                        <div class="col-md-6">
                            <select id="relevance" type="text" class="form-control @error('relevance') is-invalid @enderror" name="relevance" value="{{ old('relevance') }}">
                            <option></option>
                            <option value="-1"  @if((old('relevance') && old('relevance')==-1) || ($relevance && $relevance==-1)) selected @endif>All Categories, except Egora</option>
                            @foreach($nations as $nation)
                            <option @if((old('relevance') && old('relevance')==$nation->id) || ($relevance && $relevance==$nation->id)) selected @endif value="{{$nation->id}}">{{$nation->title}}</option>
                            @endforeach
                            </select>

                            @error('nation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>                    
                    
                    <div class="form-group row">
                        <label for="relevance" class="offset-1 col-md-2 col-form-label text-md-right">{{ __('Another Nation:') }}</label>

                        <div class="col-md-6">
                            <select id="another_nation" type="text" class="form-control @error('another_nation') is-invalid @enderror" name="another_nation" value="{{ old('another_nation') }}">
                            <option></option>
                            @foreach($all_nations as $nation)
                            <option @if((old('another_nation') && old('another_nation')==$nation->id) || ($another_nation && $another_nation==$nation->id)) selected @endif value="{{$nation->id}}">{{$nation->title}}</option>
                            @endforeach
                            </select>

                            @error('another_nation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>                    

                    <div class="form-group row">
                        <label for="search" class="col-md-3 col-form-label text-md-right">{{ __('Containing text:') }}</label>

                            @csrf
                            <div class="col-md-6">
                                <input id="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search')?: $search }}">

                                @error('search')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="unverified" class="col-md-5 col-form-label text-md-right">{{ __('Include support for ideas from unverified users:') }}</label>

                        <div class="col-md-1">
                            <input class="form-control-sm form-check-input" id="unverified" name="unverified" value=1 type="checkbox" {{ (old('unverified')?: $unverified) ? ' checked' : '' }} >
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-1 offset-3">
                            <div class="input-group">
                                <button type='submit' class='btn btn-sm btn-primary'>{{__('some.Search')}}</button>
                            </div>
                        </div>
                    </div>
                </form>