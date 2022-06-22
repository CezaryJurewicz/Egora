@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            @include('blocks.ip_left')                 
                        </div>
                        
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-12 col-md-8 offset-md-2">
                                    <div class="text-center">
                                        <h3>{{ __('views.About Me') }}</h3>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 text-right">
                                    <a class="col-12 btn btn-sm btn-primary" href="{{ route('users.ideological_profile', $user->active_search_names->first()->hash) }}">IP</a>
                                </div>
                            </div>
                            <form action="{{ route('users.about_store', $user->active_search_names->first()->hash) }}" method="POST">
                                @csrf
                                <div>
                                    <div class="card">
                                        <div class="card-body">
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" rows="10" required autofocus>{{ old('content') ?: $user->about_me }}</textarea>
                                                        @error('content')
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
                                    <div class="col-12 col-md-2 text-right offset-md-10 p-3">
                                        <button type='submit' class='col-12 btn btn-sm btn-primary mt-2'>{{__('some.Save')}}</button>
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

