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
                        <label for="nation" class="col-md-2 col-form-label text-md-right">{{ __('Relevance') }}</label>

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
                            <label for="nation" class="col-md-2 col-form-label text-md-right">{{ __('Assign Point Position') }}</label>

                            <div class="col-md-10">
                                <div class="form-group input-group row pl-3">
                                    <select id="position1" type="text" class="mr-1 col-md-3 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                        <option></option>
                                        @for($i=23; $i>0; $i--)
                                        <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif 
                                                @if(old('position1') && old('position1')== $i) selected @endif
                                                value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>

                                    <select id="position2" type="text" class="mr-3 col-md-3 form-control @error('position2') is-invalid @enderror" name="position2" value="{{ old('position2') }}">
                                        <option></option>
                                        @for($i=1; $i<24; $i++)
                                        <option @if(in_array($i, $zeros)) style="background-color: lightgray;" disabled @endif value="0">0</option>
                                        @endfor
                                    </select>
                                    
                                    <button type="submit" class="btn btn-primary col-md-3">
                                        {{ __('Save & Close') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    <div class="row mt-3">
                        <div class="col-md-3 offset-4">
                            <a class='btn btn-primary btn-sm btn-block' href="{{  url()->previous() }}">{{__('some.Cancel and Close')}}</a>
                        </div>
                    </div>
                </div>
            </div>                
            </form>
            
        </div>
    </div>
</div>
@endsection
