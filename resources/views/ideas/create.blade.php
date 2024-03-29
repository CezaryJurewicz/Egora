@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h3>@lang('Create New Idea')</h3>
            </div>
            <div class="row col-md-3 mb-3">
                <a class='btn btn-primary btn-sm btn-block' href="javascript:history.back()">{{__('some.Cancel and Close')}}</a>
            </div>
            
            <form id="create" method="POST" action="{{ route('ideas.store') }}">
                @csrf

            @if (isset($idea))
            <input type="hidden" name="idea_id" value="{{ $idea->id }}"/>
            @endif
            <input type="hidden" name="egora_id" value="{{ current_egora_id() }}"/>
            <div class="card">
                <div class="card-header">
                    <div class="form-group row">
                        <label for="nation" class="col-md-2 col-form-label text-md-middle">{{ __('Relevance:') }}</label>

                        <div class="col-md-10">
                            @if (is_egora())
                                <select id="nation" type="text" class="form-control @error('nation') is-invalid @enderror" name="nation" value="{{ old('nation') }}" required autocomplete="nation" >
                                @foreach($nations as $nation)
                                <option @if(old('nation') && old('nation')== $nation->id || (isset($nation_id) && $nation_id == $nation->id)) selected @endif value="{{$nation->id}}">{{$nation->title}}</option>
                                @endforeach
                                </select>

                                @error('nation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @elseif (is_egora('community'))
                                <select @if (!isset($copy)) disabled='disabled' @endif  id="community" type="text" class="form-control @error('community') is-invalid @enderror" name="community" value="{{ old('community') }}" required autocomplete="community" >
                                @foreach($user->communities as $c)
                                <option @if(old('community') && old('community')== $c->id || $community_id == $c->id) selected @endif value="{{$c->id}}">{{$c->title}}</option>
                                @endforeach
                                </select>
                            
                                @if (isset($community_id))
                                <input type="hidden" id="community" name="community" value="{{$community_id}}">
                                @endif
                                
                                @error('community')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror                            
                            @elseif (is_egora('municipal'))
                                <div class="mt-2">
                                    {{ $user->municipality->title }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" rows="23" required autocomplete="content" autofocus 
placeholder="In Egora an idea is primarily just a tool for solving our common problems. 
Share with us your best tools so we can build the best possible world together! 

You may take credit for your idea, but all text must be copyleft and will remain protected by copyleft.">{{ old('content') ?: $text ?? '' }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="position1" class="col-md-2 col-form-label">{{ __('Assign Position:') }}</label>

                            <select id="position1" type="text" class="col-md-2 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                <option></option>
                                @if (is_egora())
                                <optgroup label="Point Positions">
                                @for($i=23; $i>0; $i--)
                                <option @if(in_array($i+23, $numbered)) style="background-color: lightgray;" disabled @endif 
                                        @if(old('position1') && old('position1')== $i+23) selected @endif
                                        value="{{$i+23}}">{{$i}}</option>
                                @endfor
                                </optgroup>
                                <optgroup label="0-Point Positions">
                                @for($i=23; $i>0; $i--)                                        
                                <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                        @if(old('position1') && old('position1')== $i) selected @endif
                                        value="{{$i}}">0 ({{$i}})</option>
                                @endfor
                                </optgroup>
                                
                                <optgroup label="Egora Ideas">
                                @foreach([-1=>'E',-2=>'G',-3=>'O',-4=>'R',-5=>'A'] as $i=>$v)                                        
                                <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                        @if(old('position1') && old('position1')== $i) selected @endif
                                        value="{{$i}}">{{$v}}</option>
                                @endforeach
                                </optgroup>
                                    
                                @elseif (is_egora('community') || is_egora('municipal'))
                                <optgroup label="Supporting [+]">
                                    @for($i=23; $i>0; $i--)                                        
                                    <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                             @if(old('position1') && old('position1')== $i) selected @endif
                                             value="{{$i}}">({{$i}})</option>
                                    @endfor
                                </optgroup>
                                @endif
                            </select>

                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-primary col-md-6">
                                    {{ __('Publish') }}
                                </button>
                            </div>
                        </div>  
                </div>
            </div>                
            </form>
            <div class="row col-md-3 mb-3 mt-3">
                <a class='btn btn-primary btn-sm btn-block' href="javascript:history.back()">{{__('some.Cancel and Close')}}</a>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('content').onkeydown = function(e){ // TODO: change to React?
        if(e.keyCode==9 || e.which==9){
            e.preventDefault();
            var s = this.selectionStart;
            this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
            this.selectionEnd = s+1; 
        }
    }
    
    document.getElementById('community').onchange = function(e){ // TODO: change to React
        try {
            const url = new URL(window.location.href)
            
            if (url.searchParams.get('community_id') == null) {
                window.location.href = url.href + "?community_id="+this.value;
            } else {
                const regex = /community_id=[0-9]*/;
                window.location.href = url.href.replace(regex, 'community_id='+this.value);
            }
        } catch (error) {
            console.log(error); // TypeError, "Failed to construct URL: Invalid URL"
        }
    }
</script>
@endsection
