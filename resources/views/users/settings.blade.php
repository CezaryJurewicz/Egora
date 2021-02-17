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
                                
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="password" class="col-form-label">{{ __('Provide Your Current Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('current') is-invalid @enderror" name="password" value="" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->active_search_names->first()->hash) }}">Back</a>
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
                                        <label for="password_confirmation" class="col-form-label">{{ __('Confirm New') }}</label>
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->active_search_names->first()->hash) }}">Back</a>
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
                            <h4 class="pb-1">@lang('Privacy')</h4>
                            <form method="POST" action="{{ route('users.update_privacy', $user->id) }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PUT"/>
                                @csrf

                                <h5 class="pt-1">@lang('Profile Searchability:')</h5>
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
                                
                                <h5 class="pt-1">@lang('Profile Visibility (Main Egora only):')</h5>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="visible" class="col-form-lable col-md-10">{{ __('Public Profile (your support for ideas is public)') }}</label>
                                        <input  id="visible" name="visible" value=1 type="radio" {{ (old('visible')?: $user->visible) ? ' checked' : '' }} >
                                    </div>
                                    <div class="row">
                                        <label for="visible" class="col-form-lab1e col-md-10">{{ __('Hidden Profile (your support for ideas is hidden; profile is hidden from "New Users"; profile is hidden from published "Leads".)') }}</label>
                                        <input id="visible" name="visible" value=0 type="radio" {{ (old('visible')?: ($user->visible==0)) ? ' checked' : '' }} >
                                    </div>
                                </div>
                                
                                
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->active_search_names->first()->hash) }}">Back</a>
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
                                
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->active_search_names->first()->hash) }}">Back</a>
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
                    @endif
                    
                    @if( auth('web')->check() && auth('web')->user()->can('delete',$user) )
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Delete Account')</h4>
                            <form method="POST" action="{{ route('users.delete_by_user', $user->id) }}" enctype="multipart/form-data">
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
                                
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->active_search_names->first()->hash) }}">Back</a>
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
                    @else
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Delete Account')</h4>
                                <p class="col-md-12 row">Account deletion is not available to ILP Members nor former ILP members in
                                order to protect the interests of the current ILP Members.</p>
                                <p class="col-md-12 row">However, if you wish to minimize your presence in Egora, you can do so by
                                changing your registered name, changing your Search Name, changing your
                                email address, deleting your public contact information, and removing all of
                                your ideas. Nevertheless, if you verified your Egora account using a
                                government ID, your ID number––associated to your Egora Account
                                Number––will be maintained externally as a secure record.</p>
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

