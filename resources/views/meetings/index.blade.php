@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Meetings') }}</h3>
                <div>
                    @if($meetings->isNotEmpty()) 
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                    <th scope="col">{{ __('tables.User')}}</th>
                                    <th scope="col">{{ __('tables.Address')}}</th>
                                    <th scope="col">{{ __('tables.Topic')}}</th>
                                    <th scope="col">{{ __('tables.Comments')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($meetings as $i=>$meeting)
                                <tr>
                                    <th scope="row">{{$meeting->id}}</th>
                                    <td>{{ $meeting->user->nation->title }}</td>
                                    <td>{{ $meeting->user->name }}</td>
                                    <td>{{ $meeting->country->name }}, {{ $meeting->city->name }}, {{ $meeting->address }}</td>
                                    <td>{{ $meeting->topic }}</td>
                                    <td>{{ $meeting->comments }}</td>
                                    <td>
                                        <form action="{{ route('petitions.delete',['id'=>$meeting->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('meetings.No meetings')</p>
                    @endforelse
                    
                    @if($meetings->isNotEmpty())                    
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

