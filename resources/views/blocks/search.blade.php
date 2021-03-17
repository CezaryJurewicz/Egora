                <form autocomplete="off" action="{{ route(Route::current()->getName()) }}" method="GET">
                    
                    @if (is_egora())
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
                        <label for="relevance" class="offset-1 col-md-2 col-form-label text-md-right">{{ __('And:') }}</label>

                        <div class="col-md-6">
                            <div id="NationSearch" value="{{  $nation }}"></div>

                            @error('nation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>          
                    @elseif (is_egora('community'))
                        <div class="form-group row">
                            <label for="community" class="offset-2 col-md-1 col-form-label text-md-right">{{ __('Relevance:') }}</label>
                            <div class="col-md-6">
                                <select id="community" type="text" class="form-control @error('community') is-invalid @enderror" name="community" value="{{ old('community') ?: $community }}">
                                @foreach($user->communities as $c)
                                <option @if((old('community') && old('community')==$c->id) || ($community && $community==$c->id) || ($community==0 && $community==$c->id)) selected @endif value="{{$c->id}}">{{$c->title}}</option>
                                @endforeach
                                </select>

                                @error('relevance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                    
                    @elseif (is_egora('municipal'))
                        <div class="form-group row">
                            <label for="relevance" class="offset-2 col-md-1 col-form-label text-md-right">{{ __('Relevance:') }}</label>
                            <div class="col-md-6">
                                <select id="relevance" type="text" class="form-control @error('relevance') is-invalid @enderror" name="relevance" value="{{ old('relevance') ?: $relevance }}">
                                <option @if((old('relevance') && old('relevance') == $user->municipality->id) || ($relevance && $relevance == $user->municipality->id)) selected @endif value="{{$user->municipality->id}}">{{$user->municipality->title}}</option>
                                <option @if((old('relevance') && old('relevance') == -1) || ($relevance && $relevance == -1)) selected @endif value="-1">-</option>
                                </select>

                                @error('relevance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                    
                    
                    <div class="form-group row">
                        <label for="municipality" class="offset-1 col-md-2 col-form-label text-md-right">{{ __('And:') }}</label>

                        <div class="col-md-6">
                            <div id="MunicipalitySearch" value="{{  $municipality }}"></div>

                            @error('municipality')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>          
                    
                    
                    @endif

                    <div class="form-group row">
                        <label for="search" class="col-md-3 col-form-label text-md-right">{{ __('Containing text:') }}</label>

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
                        <div class="col-md-12 text-center">
                            <button type='submit' class='btn btn-sm btn-primary btn-static-200'>{{__('Display')}}</button>
                        </div>
                    </div>
                </form>