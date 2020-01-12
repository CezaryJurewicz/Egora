@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <div class="text-center">
                    <h3>{{ __('Personal Information') }}</h3>
                </div>
                <div class="col-centered col-md-6">
                    <form autocomplete="off" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT"/>
                        @csrf

                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('Your Name') }} <small>({{ __('Changing your name will require re-verification with a government ID') }})</small> </label>

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
                            <label for="title" class="col-form-label">{{ __('Search Name') }} <small>({{ __('Changing your Search Name allows you to lose all of your followers') }})</small></label>
                            <div>
                                <input id="search_name" type="text" class="form-control @error('search_name') is-invalid @enderror" name="search_name" value="{{ old('search_name')?: $searchName->name }}" required>

                                @error('search_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nation" class="col-form-label">{{ __('Nation') }}</label>

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
                            <label for="contacts" class="col-form-label">{{ __('Your Public Contact Information') }}</label>

                            <div>
                                <textarea id="contacts" type="text" class="form-control @error('contacts') is-invalid @enderror" name="contacts">{{ old('contacts') ?: $user->contacts  }}</textarea>

                                @error('contacts')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr class="mt-4">
                        
                        <div class="form-group">
                            <label for="current_password" class="col-form-label">{{ __('Provide Your Current Password') }}</label>

                            <div>
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autocomplete="off">

                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row mt-4">
                            <div class="col-md-2">
                                <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->id) }}">Back</a>
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
@endsection

