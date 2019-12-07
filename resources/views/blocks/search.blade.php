                <form action="{{ route(Route::current()->getName()) }}" method="POST">
                    <div class="form-group row">
                        <label for="relevance" class="offset-2 col-md-1 col-form-label text-md-right">{{ __('Relevance:') }}</label>

                        <div class="col-md-6">
                            <select id="relevance" type="text" class="form-control @error('relevance') is-invalid @enderror" name="relevance" value="{{ old('relevance') }}">
                            @foreach($nations as $title=>$id)
                            <option @if((old('relevance') && old('relevance')==$id) || ($relevance && $relevance==$id) || ($relevance==0 && $relevance==$id)) selected @endif value="{{$id}}">{{$title}}</option>
                            @endforeach
                            </select>

                            @error('relevance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>                    
                    
                    <div class="form-group row">
                        <label for="relevance" class="offset-1 col-md-2 col-form-label text-md-right">{{ __('Another Nation:') }}</label>

                        <div class="col-md-6">
                            <div id="NationSearch" value="{{  $nation }}"></div>

                            @error('nation')
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
                                <button type='submit' class='btn btn-sm btn-primary'>{{__('some.Generate')}}</button>
                            </div>
                        </div>
                    </div>
                </form>