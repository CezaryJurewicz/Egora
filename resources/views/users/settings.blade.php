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
                            <form method="POST" action="{{ route('users.update_email_send_token', $user->id) }}" enctype="multipart/form-data">
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

                                <div class="form-group">
                                    <a class="btn btn-black btn-sm col-md-2" href="{{ route('users.ideological_profile', $user->id) }}">Back</a>
                                    <button type="submit" class="btn btn-secondary btn-sm col-md-2 offset-1">
                                        {{ __('Save') }}
                                    </button>
                                </div>      
                            </form>
                        </div>
                    </div>
                    <hr/>

                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Password')</h4>
                            <form method="POST" action="{{ route('users.update_password', $user->id) }}" enctype="multipart/form-data">
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
                                        <label for="password_confirmation" class="col-form-label">{{ __('Current') }}</label>
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <a class="btn btn-black btn-sm col-md-2" href="{{  route('users.ideological_profile', $user->id) }}">Back</a>
                                    <button type="submit" class="btn btn-secondary btn-sm col-md-2 offset-1">
                                        {{ __('Save') }}
                                    </button>
                                </div>      
                            </form>
                        </div>
                    </div>
                    <hr/>

                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Privacy')</h4>
                            <form method="POST" action="{{ route('users.update_privacy', $user->id) }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PUT"/>
                                @csrf

                                <div class="form-group">
                                    <div class="row">
                                        <label for="seachable" class="col-form-lable col-md-10">{{ __('Public Profile (searchable with partial Search Name match)') }}</label>
                                        <input  id="seachable" name="seachable" value=1 type="radio" {{ (old('seachable')?: $user->active_search_names->first()->seachable) ? ' checked' : '' }} >
                                    </div>
                                    <div class="row">
                                        <label for="seachable" class="col-form-lab1e col-md-10">{{ __('Hidden Profile (searchable with strict Search Name match)') }}</label>
                                        <input id="seachable" name="seachable" value=0 type="radio" {{ (old('seachable')?: ($user->active_search_names->first()->seachable==0)) ? ' checked' : '' }} >
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <a class="btn btn-black btn-sm col-md-2" href="{{  route('users.ideological_profile', $user->id) }}">Back</a>
                                    <button type="submit" class="btn btn-secondary btn-sm col-md-2 offset-1">
                                        {{ __('Save') }}
                                    </button>
                                </div>      
                            </form>
                        </div>
                    </div>
                    <hr/>
                    
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Notifications')</h4>
                            <p>Egora does not currently send any automated notifications.</p>
                        </div>
                    </div>
                    <hr/>

                    @if( auth('web')->check() && auth('web')->user()->can('deactivate',$user) )
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Deactivate Account')</h4>
                            <form method="POST" action="{{ route('users.deactivate', $user->id) }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PUT"/>
                                @csrf

                                <p class="col-md-12 row">Deactivating your account will remove your profile from public visibility. All of your information will be maintained internally,
                                but your Ideological Profile will cease to give any support to any of your ideas on the Indexes. Your profile will become reactivated 
                                upon next login.</p>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="deactivate" class="col-form-lable col-md-10">{{ __('Do you really wish to deactivate your account?') }}</label>
                                        <input  id="deactivate" name="deactivate" value=1 type="checkbox" {{ old('deactivate') ? ' checked' : '' }} >
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <a class="btn btn-black btn-sm col-md-2" href="{{  route('users.ideological_profile', $user->id) }}">Back</a>
                                    <button type="submit" class="btn btn-secondary btn-sm col-md-2 offset-1">
                                        {{ __('Save') }}
                                    </button>
                                </div>      
                            </form>
                        </div>
                    </div>
                    <hr/>
                    @endif
                    
                    @if( auth('web')->check() && auth('web')->user()->can('delete',$user) )
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Delete Account')</h4>
                            <form method="POST" action="{{ route('users.delete', $user->id) }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="DELETE"/>
                                @csrf

                                <p class="col-md-12 row">Deleting your account will remove all of your personal information from Egora, including
                                your name, email address, public contact information and Ideological Profile content.
                                You will have to create a new account to use Egora again. This option is only available if you have never declared yourself 
                                as a member of the International Logic Party.</p>

                                <div class="form-group">
                                    <div class="row">
                                        <label for="delete" class="col-form-lable col-md-10">{{ __('Do you really wish to delete your account?') }}</label>
                                        <input  id="delete" name="delete" value=1 type="checkbox" {{ old('delete') ? ' checked' : '' }} >
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <a class="btn btn-black btn-sm col-md-2" href="{{  route('users.ideological_profile', $user->id) }}">Back</a>
                                    <button disabled="" type="submit" class="btn btn-secondary btn-sm col-md-2 offset-1">
                                        {{ __('Save') }}
                                    </button>
                                </div>      
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Delete Account')</h4>
                                <p class="col-md-12 row">Account deletion is not available to ILP Members nor former ILP Members in order 
                                to protect interests of current ILP Members. However, if you wish to minimize your presence in the Egora, 
                                you can do so by changing your name, deleting all personal information and deactivating your account.
                                Nevertheless, a minimal history of your identity and your ILP status will be maintained externally as a secure
                                record for the benefit of the public.</p>
                                <p class="col-md-12 row">If you decide to return to using Egora, you will be able to reactivate your account and
                                rebuild your public presence</p>
                        </div>
                    </div>
                    @endif
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

