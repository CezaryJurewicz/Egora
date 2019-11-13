@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.User') }}</h3>
                <div>
                    <div>Type: {{ $user->user_type->title }}</div>
                    <div>User: {{ $user->name }}</div>
                    <div>Email: {{ $user->email }}</div>
                    <div>Search Name: @if(null !== $user->active_search_names->first()) {{ $user->active_search_names->first()->name }} @else - @endif
                        
                    @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                    <a class="btn btn-sm btn-warning" href="{{ route('search_names.create') }}">Create</a>
                    <!--<a class="btn btn-sm btn-warning" href="{{ route('search_names.edit', $user->id) }}">Edit</a>-->
                    @endif
                    
                    </div>
                    <div>Nation: {{ $user->nation->title }}</div>
                    <div class="mt-3">Ideas:</div>
                    @foreach($user->ideas as $idea)
                    <div>
                        <a href="{{ route('ideas.view', $idea->id) }}">#{{$idea->id}} {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...</a>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-3 row">
                    <div class="col-md-1">
                        <a class="btn btn-sm btn-primary" href="{{  url()->previous() }}">Back</a>
                    </div>
                    @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                    <div class="col-md-1">
                        <a class="btn btn-sm btn-warning" href="{{ route('users.edit', $user->id) }}">Edit</a>
                    </div>
                    @endif
                    
                    @if ((auth('web')->user()?:auth('admin')->user())->can('delete', $user))
                    <div class="col-md-1">
                        <form action="{{ route('users.delete',$user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                            <div class="input-group">
                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            <div class="mt-3">
                @if($user->image)
                <h5 class="mb-1">{{ __('media.Profile photo')}}</h5>
                <img src="{{ Storage::url($user->image->filename) }}"  width1="250" class="img-fluid img-thumbnail" alt=""> 

                    @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                    <div class="mt-3">
                        <form action="{{ route('media.delete',$user->image->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                            <div class="input-group">
                                <button type='submit' class='btn btn-warning'>{{__('some.Delete')}}</button>
                            </div>
                        </form>
                    </div>
                    @endif
                @else
                <h5 class="mb-1">{{ __('media.Upload your photo')}}</h5>

                <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" name="file" id="file"/>
                        <input type="hidden" name="type" value="image"/>
                        <button type='submit' class='btn btn-primary'>{{__('some.Upload')}}</button>
                    </div>
                </form>
                @endif
            </div>
            
            <div class="mt-3">
                @if($user->verification_id)
                <h5 class="mb-1">{{ __('media.Verification id photo')}}</h5>
                <img src="{{ Storage::url($user->verification_id->image->filename) }}"  width1="250" class="img-fluid img-thumbnail" alt=""> 
                
                    @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                    <div class="mt-3">
                        <form action="{{ route('passports.delete',$user->verification_id->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                            <div class="input-group">
                                <button type='submit' class='btn btn-warning'>{{__('some.Delete')}}</button>
                            </div>
                        </form>
                    </div>
                    @endif
                    
                    @if (auth('admin')->user() && !$user->user_type->verified)
                    <div class="mt-3">
                        <form action="{{ route('users.verify',$user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT"/>
                            <div class="input-group">
                                <button type='submit' class='btn btn-warning'>{{__('some.Verify')}}</button>
                            </div>
                        </form>
                    </div>
                    @endif
                    
                    @if (auth('admin')->user() && $user->user_type->verified)
                    <div class="mt-3">
                        <form action="{{ route('users.unverify',$user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE"/>
                            <div class="input-group">
                                <button type='submit' class='btn btn-warning'>{{__('some.Unverify')}}</button>
                            </div>
                        </form>
                    </div>
                    @endif
                    
                @else
                    @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                    <h5 class="mb-1">{{ __('media.Upload verification id photo')}}</h5>

                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="file" name="file" id="file"/>
                            <input type="hidden" name="type" value="passport"/>
                            <button type='submit' class='btn btn-primary'>{{__('some.Upload')}}</button>
                        </div>
                    </form>
                    @endif
                @endif
            </div>

        </div>
    </div>
    </div>
</div>
@endsection

