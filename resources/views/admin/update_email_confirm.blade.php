@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <h3>{{ __('views.Settings') }}</h3>
                    
                    <div class="panel mt-4 mb-4">
                        <div class="panel-body">
                            <h4>@lang('Email')</h4>
                            <form method="POST" action="{{ route('admin.update_email', $token) }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PUT"/>
                                @csrf

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <p class="col-form-label">{{ __('Are you sure you like to change your old email "'. $user->email.'" to new email "'. $user->another_email .'"?') }}</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <a class="btn btn-black btn-sm col-md-1" href="{{ route('admin.settings') }}">No</a>
                                    <button type="submit" class="btn btn-secondary btn-sm col-md-1 offset-1">
                                        {{ __('Yes') }}
                                    </button>
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

