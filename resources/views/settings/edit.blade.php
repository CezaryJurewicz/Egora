@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9 offset-1">
                            <div class="row">
                                <div class="col col-md-8 offset-2">
                                    <div class="text-center">
                                        <h3>{{ __('Edit Control Value') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('settings.update', $setting->id) }}" method="POST">
                                @csrf
                                <div>
                                    <div class="card">
                                        <div class="card-body">
                                                @if ($setting->type == 'text')
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <textarea id="value" class="form-control @error('value') is-invalid @enderror" name="value" rows="23" autofocus>{{ old('value') ?: $setting->value }}</textarea>
                                                        @error('value')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                        <div><a href="http://en.wikipedia.com/wiki/Markdown" target="_blank">Markdown help</a></div>
                                                    </div>
                                                </div>
                                                @elseif ($setting->type == 'string')
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <input type="text" id="value" class="form-control @error('value') is-invalid @enderror" name="value" rows="23" autofocus value="{{ old('value') ?: $setting->value }}">
                                                        @error('value')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @elseif ($setting->type == 'boolean')
                                                <h5 class="pt-1">{{ $setting->name }}</h5>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label for="value" class="col-form-lable col-md-10">{{ __('Yes') }}</label>
                                                        <input  id="value" name="value" value=1 type="radio" {{ (old('value')?: $setting->value ) ? ' checked' : '' }} >
                                                    </div>
                                                    <div class="row">
                                                        <label for="value" class="col-form-lab1e col-md-10">{{ __('No') }}</label>
                                                        <input id="value" name="value" value=0 type="radio" {{ (old('value')?: ($setting->value ==0)) ? ' checked' : '' }} >
                                                    </div>
                                                </div>

                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-md-2 text-right offset-10 p-3">
                                        <button type='submit' class='btn btn-sm btn-primary mt-2'>{{__('some.Save')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

