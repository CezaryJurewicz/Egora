@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-10 offset-1 text-center">
                        <h3>{{ __('Communities') }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                    <a class='btn btn-primary btn-sm btn-block' href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">{{__('Close')}}</a>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="card p-5">
                    
                <form autocomplete="off" method="POST" action="{{ route('users.communities_update', $user->active_search_name_hash) }}" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT"/>
                    @csrf
                    
                    @for($i=0; $i<23; $i++)
                        <div class="form-group row">
                            <div class="col-12 col-md-11 p-0">
                                <input id="ci{{$i}}" type="text" class="form-control @error('communities.'.$i) is-invalid @enderror" name="communities[{{$i}}]" value="{{ old('communities.'.$i)?: (isset($communities[$i]) ? $communities[$i]->title : '') }}">

                                @error('communities.'.$i)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-1 col-12 p-0">
                            <button class="btn btn-primary btn-sm pl-1 pb-2 pt-2 col-12">{{ __('Save') }}</button>
                            </div>

                        </div>
                    @endfor
                </form>
                </div>
                
                <div class="row">
                    <div class="col-3 mt-3">
                    <a class='btn btn-primary btn-sm btn-block' href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">{{__('Close')}}</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</div>
@endsection

