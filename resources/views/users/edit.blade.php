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
                            <label for="title" class="col-form-label">{{ __('Search-Name') }} <small>({{ __('Changing your Search-Name allows you to lose all of your followers') }})</small></label>
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
                            <div>
                                <label for="delete_followers" class="col-form-lable col-8 col-md-10">{{ __('Lose all of my followers') }}</label>
                                <input  id="delete_followers" name="delete_followers" value=1 type="checkbox" {{ old('delete_followers') ? ' checked' : '' }} >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nation" class="col-form-label">{{ __('Nation') }} <small>({{ __('Changing your nation will remove former nation\'s ideas from your IP') }})</small></label>

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
                            <label for="national_affiliations" class="col-form-label">{{ __('National Affiliations') }}</label>

                            <div>
                                <input id="national_affiliations" type="text" class="form-control @error('national_affiliations') is-invalid @enderror" name="national_affiliations" value="{{ old('national_affiliations') ?: $user->national_affiliations  }}" autocomplete="national_affiliations" autofocus>

                                @error('national_affiliations')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <hr class="mt-4">
                        <div class="text-center">
                            <h5>{{ __('Contact Information') }}</h5>
                        </div>
                        
                        <div class="form-group">
                            <label for="email_address" class="col-form-label">{{ __('Email Address') }}</label>
                            <div>
                                <input id="email_address" type="text" class="form-control @error('email_address') is-invalid @enderror" name="email_address" value="{{ old('email_address')?: $user->email_address }}">

                                @error('email_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone_number" class="col-form-label">{{ __('Phone Number') }}</label>
                            <div>
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number')?: $user->phone_number }}">

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="social_media_1" class="col-form-label">{{ __('Social Media 1') }}</label>
                            <div>
                                <input id="social_media_1" type="text" class="form-control @error('social_media_1') is-invalid @enderror" name="social_media_1" value="{{ old('social_media_1')?: $user->social_media_1 }}">

                                @error('social_media_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="social_media_2" class="col-form-label">{{ __('Social Media 2') }}</label>
                            <div>
                                <input id="social_media_2" type="text" class="form-control @error('social_media_2') is-invalid @enderror" name="social_media_2" value="{{ old('social_media_2')?: $user->social_media_2 }}">

                                @error('social_media_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="messenger_1" class="col-form-label">{{ __('Messenger 1') }}</label>
                            <div>
                                <input id="messenger_1" type="text" class="form-control @error('messenger_1') is-invalid @enderror" name="messenger_1" value="{{ old('messenger_1')?: $user->messenger_1 }}">

                                @error('messenger_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="messenger_2" class="col-form-label">{{ __('Messenger 2') }}</label>
                            <div>
                                <input id="messenger_2" type="text" class="form-control @error('messenger_2') is-invalid @enderror" name="messenger_2" value="{{ old('messenger_2')?: $user->messenger_2 }}">

                                @error('messenger_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="other_1" class="col-form-label">{{ __('Other 1') }}</label>

                            <div>
                                <textarea id="other_1" type="text" class="form-control @error('other_1') is-invalid @enderror" name="other_1">{{ old('other_1') ?: $user->other_1 }}</textarea>

                                @error('other_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="other_2" class="col-form-label">{{ __('Other 2') }}</label>

                            <div>
                                <textarea id="other_2" type="text" class="form-control @error('other_2') is-invalid @enderror" name="other_2">{{ old('other_2') ?: $user->other_2 }}</textarea>

                                @error('other_2')
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
                            <div class="col-2">
                                <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->active_search_names->first()->hash) }}">Back</a>
                            </div>
                            <div class="col-4 offset-2 text-center">
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

