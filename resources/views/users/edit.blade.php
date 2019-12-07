@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.User Edit') }}</h3>
                <div>
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT"/>
                        @csrf

                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('Your Name') }}</label>

                            <div>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?: $user->name  }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nation" class="col-form-label">{{ __('Nationality') }}</label>

                            <div>
                                <div id="NationSearch" value="{{ old('nation') ?: $user->nation->title }}"></div>

                                @error('nation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contacts" class="col-form-label">{{ __('Your Contact Info') }}</label>

                            <div>
                                <input id="contacts" type="text" class="form-control @error('contacts') is-invalid @enderror" name="contacts" value="{{ old('contacts') ?: $user->contacts  }}">

                                @error('contacts')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="col-form-label">{{ __('Password') }}</label>

                            <div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-form-label">{{ __('Confirm Password') }}</label>

                            <div>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                     </form>
                    
                </div>
                
                <a class="btn btn-sm1 btn-primary mt-2" href="{{  url()->previous() }}">Back</a>

            </div>
        </div>
    </div>
    </div>
</div>
@endsection

