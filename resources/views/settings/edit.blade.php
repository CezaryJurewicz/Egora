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
                                        <h3>{{ __('Edit Setting') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('settings.update', $setting->id) }}" method="POST">
                                @csrf
                                <div>
                                    <div class="card">
                                        <div class="card-body">
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <textarea id="value" class="form-control @error('value') is-invalid @enderror" name="value" rows="10" autofocus>{{ old('value') ?: $setting->value }}</textarea>
                                                        @error('value')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
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

