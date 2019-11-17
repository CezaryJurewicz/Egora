@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Idea') }}</div>

                <div class="card-body">
                    <form id="register" method="POST" action="{{ route('ideas.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Content') }}</label>

                            <div class="col-md-8">
                                <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" rows="10" required autocomplete="content" autofocus>{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nation" class="col-md-2 col-form-label text-md-right">{{ __('Nation') }}</label>

                            <div class="col-md-8">
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
                        
                        <div class="form-group row">
                            <label for="nation" class="col-md-2 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <div class="form-group row pl-3">
                                    <select id="position1" type="text" class="col-md-2 form-control @error('position1') is-invalid @enderror" name="position1" value="{{ old('position1') }}">
                                        <option></option>
                                        @for($i=1; $i<24; $i++)
                                        <option @if(in_array($i, $numbered)) style="background-color: lightgray;" disabled @endif @if(old('position1') && old('position1')== $i) selected @endif value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>

                                    <select id="position2" type="text" class="col-md-2 form-control @error('position2') is-invalid @enderror" name="position2" value="{{ old('position2') }}">
                                        <option></option>
                                        @for($i=1; $i<24; $i++)
                                        <option @if(in_array($i, $zeros)) style="background-color: lightgray;" disabled @endif value="0">0</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                {{-- <input id="position" type="text" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ old('position') }}" req1uired autocomplete="position" autofocus>

                                @error('position')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
