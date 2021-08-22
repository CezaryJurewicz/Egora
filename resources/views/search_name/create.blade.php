@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Create Search-Name') }}</h3>
                <div>
                    <form method="POST" action="{{ route('search_names.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="title" class="col-form-label">{{ __('Name') }}</label>
                            <div>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" id="seachable" name="seachable" value=1 type="checkbox">
                                <label for="seachable" class="col-form-label">{{ __('searchable') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="active" name="active" value=1 type="checkbox">
                                <label for="active" class="col-form-label">{{ __('active') }}</label>
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

