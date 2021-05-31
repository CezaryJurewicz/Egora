@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ __('Add Guardian') }}</h3>
                        </div>
                    </div>

                    <form autocomplete="off" action="{{ route('admins.store') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <div class="offset-1 col-md-12 row">
                                <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-1 col-md-12 row">
                                <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('Login (email)') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-1 col-md-12 row">
                                <label for="password" class="col-md-2 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-1 col-md-12 row">
                                <label for="password_confirmation" class="col-md-2 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type='submit' class='btn btn-sm btn-primary btn-static-200'>{{__('some.Submit')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
