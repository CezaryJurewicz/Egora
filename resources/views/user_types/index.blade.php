@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.User Types') }}</h3>
                <div>
                    @if($user_types->isNotEmpty()) 
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Title')}}</th>
                                    <th scope="col">{{ __('tables.Subtitle')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($user_types as $i=>$user_type)
                                <tr>
                                    <th scope="row">{{$user_type->id}}</th>
                                    <td>{{ $user_type->title }}</td>
                                    <td>{{ $user_type->subtitle }}</td>
                                    <td>
                                        <form action="{{ route('user_types.delete',['id'=>$user_type->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('user_type.No user types')</p>
                    @endforelse
                    
                    @if($user_types->isNotEmpty())                    
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

