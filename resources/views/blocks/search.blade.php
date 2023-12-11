                <form autocomplete="off" action="{{ route(Route::current()->getName()) }}#ideas" method="GET">
                    @if (collect(Request::query())->has('sort'))
                        <input id="sort" type="hidden" name="sort" value="date">
                    @endif
                    
                    @guest
                    <input type="hidden" name="relevance" value="{{ collect($nations)->first() }}" />
                    @if (collect(Request::query())->has('sort'))
                    <input type="hidden" name="sort" value="{{ $sort }}" />
                    @endif
                    @if (collect(Request::query())->has('index'))
                    <input type="hidden" name="index" value="{{ $index }}" />
                    @endif
                    @endguest
                    
                    @auth
                    @if (is_egora())
                    <div class="form-group row">
                        <label for="relevance" class="offset-2 col-md-1 col-form-label text-md-right">{{ __('Relevance') }}</label>

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
                        <label for="relevance" class="offset-1 col-md-2 col-form-label text-md-right">{{ __('And') }}</label>

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
                            <label for="community" class="offset-2 col-md-1 col-form-label text-md-right">{{ __('Relevance') }}</label>
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
                            <label for="relevance" class="offset-2 col-md-1 col-form-label text-md-right">{{ __('Relevance') }}</label>
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
                        <label for="municipality" class="offset-1 col-md-2 col-form-label text-md-right">{{ __('And') }}</label>

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
                    @endauth
                    
                    <div class="form-group row">
                        <label for="search" class="col-md-3 col-form-label text-md-right">{{ __('Containing text') }}</label>

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
                        <div class="col-md-10 offset-md-1">
                            <div class="accordion mb-3" id="accordionSearch">
                                <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                                  <div class="card-header p-0" id="headingSearch">
                                    <h2 class="mb-0">
                                      <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                                        Guide
                                      </button>
                                    </h2>
                                  </div>

                                  <div id="collapseSearch" class="collapse" aria-labelledby="headingSearch" data-parent="#accordionSearch">
                                    <div class="card-body col-md-10 offset-md-1">
                                        <p>This in not a broad search function but a filter for specific terms or phrases. For example, you can enter "freedom of speech" to display an index of ideas that contain this particular text.</p>
                                        <p>Additionally, when writing an idea pertaining to a specific topic, include all of the typical phrases associated with that topic to ensure that those who are filtering for it will find it.</p>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-12 text-center">
                            <button type='submit' class='btn btn-sm btn-primary btn-static-200'>{{__('Display')}}</button>
                        </div>
                    </div>
                </form>