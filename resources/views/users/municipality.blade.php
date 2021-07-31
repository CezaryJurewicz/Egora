@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <div class="text-center">
                    <h3>{{ __('Municipality') }}</h3>
                </div>
                <div class="col-centered col-md-6">
                    <form autocomplete="off" method="POST" action="{{ route('users.municipality_update', $user->active_search_name_hash) }}" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT"/>
                        @csrf

                        <div class="form-group">
                            <div class="mt-4">
                                <div id="MunicipalitySearch" value="{{ (old('municipality') ?: ($user->municipality ? $user->municipality->title : '')) }}"></div>

                                @error('municipality')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <div class="row">
                                <div class="col-2">
                                    <a class="btn btn-black btn-sm btn-static-100" href="{{  route('users.ideological_profile', $user->active_search_name_hash) }}">Back</a>
                                </div>
                                <div class="col-4 offset-2 text-center">
                                    <button type="submit" class="btn btn-secondary btn-sm btn-static-100">
                                        {{ __('Save') }}
                                    </button>
                                </div>
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

