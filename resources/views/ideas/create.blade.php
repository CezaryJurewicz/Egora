@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h3>@lang('Create New Idea')</h3>
            </div>
            <div class="row col-md-3 mb-3">
                <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
            </div>
            
            <form id="register" method="POST" action="{{ route('ideas.store') }}">
                @csrf

            <div class="card">
                <div class="card-header">
                    <div class="form-group row">
                        <label for="nation" class="col-md-2 col-form-label text-md-middle">{{ __('Relevance') }}</label>

                        <div class="col-md-10">
                            <select id="nation" type="text" class="form-control @error('nation') is-invalid @enderror" name="nation" value="{{ old('nation') }}" required autocomplete="nation" >
                            <option></option>
                            @foreach($nations as $nation)
                            <option @if(old('nation') && old('nation')== $nation->id) selected @endif value="{{$nation->id}}">{{$nation->title}}</option>
                            @endforeach
                            </select>

                            @error('nation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" rows="10" required autocomplete="content" autofocus>{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="position1" class="col-md-3 col-form-label text-md-right">{{ __('Assign Point Position') }}</label>

                            <div class="col-md-9">
                                <div class="form-group input-group row pl-3">
                                    <select id="position1" type="text" class="mr-1 col-md-3 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                        <option></option>
                                        <optgroup label="Point positions">
                                        @for($i=23; $i>0; $i--)
                                        <option @if(in_array($i+23, $numbered)) style="background-color: lightgray;" disabled @endif 
                                                @if(old('position1') && old('position1')== $i+23) selected @endif
                                                value="{{$i+23}}">{{$i}}</option>
                                        @endfor
                                        </optgroup>
                                        <optgroup label="Non-Point positions">
                                        @for($i=23; $i>0; $i--)                                        
                                        <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                                @if(old('position1') && old('position1')== $i) selected @endif
                                                value="{{$i}}">0 ({{$i}})</option>
                                        @endfor
                                            </optgroup>
                                    </select>
                                    
                                    <button type="submit" class="btn btn-primary col-md-3">
                                        {{ __('Save & Close') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    <div class="row mt-3">
                        <div class="col-md-4 offset-4">
                            <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                        </div>
                    </div>
                </div>
            </div>                
            </form>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('content').onkeydown = function(e){
        if(e.keyCode==9 || e.which==9){
            e.preventDefault();
            var s = this.selectionStart;
            this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
            this.selectionEnd = s+1; 
        }
    }
</script>
@endsection
