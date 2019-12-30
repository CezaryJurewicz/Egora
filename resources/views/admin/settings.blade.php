@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <div class="text-center">
                    <h3>{{ __('views.Settings') }}</h3>
                    </div>
                    <div class="col-centered col-md-6">
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Email')</h4>
                            <form method="POST" action="{{ route('admin.update_email_send_token') }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PUT"/>
                                @csrf

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?: $user->email  }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="email" class="col-form-label">{{ __('A confirmation email will be sent after you click "Save".') }}</label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <a class="btn btn-black btn-sm btn-static-100" href="{{  url()->previous() }}">Back</a>
                                    </div>
                                    <div class="col-md-8 text-center">
                                        <button type="submit" class="btn btn-secondary btn-sm btn-static-100">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>          
                            </form>
                        </div>
                    </div>
                    <hr/>

                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Password')</h4>
                            <form method="POST" action="{{ route('admin.update_password') }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PUT"/>
                                @csrf

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="current" class="col-form-label">{{ __('Current') }}</label>
                                        <input id="current" type="password" class="form-control @error('current') is-invalid @enderror" name="current" value="" required>
                                        @error('current')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="password" class="col-form-label">{{ __('New') }}</label>
                                        <input id="current" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="password_confirmation" class="col-form-label">{{ __('Confirm New') }}</label>
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <a class="btn btn-black btn-sm btn-static-100" href="{{  url()->previous() }}">Back</a>
                                    </div>
                                    <div class="col-md-8 text-center">
                                        <button type="submit" class="btn btn-secondary btn-sm btn-static-100">
                                            {{ __('Save') }}
                                        </button>
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
</div>
@endsection

